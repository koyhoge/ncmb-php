<?php
namespace NCMB;

use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;

// @see https://gist.github.com/Dynom/5866837
// @see https://gist.github.com/kaz29/7380772

class NCMBAPIClient
{
    private $client;
    private $config = array(
        'apiUrl' => 'https://mb.api.cloud.nifty.com',
        'apiVersion' => '2013-09-01',
    );

    public static function create($applicationKey, $clientKey)
    {
        $config = array(
            'applicationKey' => $applicationKey,
            'clientKey' => $clientKey,
        );
        return new self($config);
    }

    public function __construct(array $config, GuzzleHttp\ClientInterface $client = null)
    {
        $this->config = array_merge($this->config, $config);
        $this->client = $client ? $client : new GuzzleHttp\Client();
    }

    public function get($path, $options = array())
    {
        return $this->send('GET', $path, $options);
    }

    public function post($path, array $options = array())
    {
        return $this->send('POST', $path, $options);
    }

    public function put($path, array $options = array())
    {
        return $this->send('PUT', $path, $options);
    }

    public function send($method, $path, array $options = array())
    {
        $url = $this->createAbsURL($path);
        $headers = $this->createDefaultHeaders($method, $url, $options);
        $this->client->setDefaultOption('headers', $headers);

        return $this->client->send($this->client->createRequest($method, $url, $options));
    }

    protected function createAbsURL($path)
    {
        return sprintf('%s/%s%s', $this->config['apiUrl'], $this->config['apiVersion'], $path);
    }

    protected function createDefaultHeaders($method, $url, array $options = array())
    {
        $applicationKey = $this->config['applicationKey'];
        $timestamp = $this->timestamp();
        $query = isset($options['query']) ? $options['query'] : array();

        $sign = $this->sign($method, $url, $query, $timestamp);

        $headers = array(
            'X-NCMB-Application-Key' => $applicationKey,
            'X-NCMB-Signature' => $sign,
            'X-NCMB-Timestamp' => $timestamp,
        );

        return $headers;
    }

    protected function timestamp()
    {
        $time = microtime(true);
        $microSeconds = sprintf("%06d", ($time - floor($time)) * 1000000);
        $dt = new \DateTime(date('Y-m-d H:i:s.'. $microSeconds, $time), new \DateTimeZone('UTC'));
        $timestamp = sprintf(
            '%s.%03dZ',
            $dt->format('Y-m-d\TH:i:s'),
            floor($dt->format('u') / 1000)
        );

        return $timestamp;
    }

    protected function sign($method, $url, $params, $timestamp)
    {
        $application_key = $this->config['applicationKey'];
        $client_key = $this->config['clientKey'];

        $params['SignatureMethod'] = 'HmacSHA256';
        $params['SignatureVersion'] = 2;
        $params['X-NCMB-Application-Key'] = $application_key;
        $params['X-NCMB-Timestamp'] = $timestamp;

        uksort($params, 'strnatcmp');

        $encoded_params = '';
        foreach ($params as $k => $v) {
            if ($k === 'X-NCMB-Timestamp') {
                $encoded_params[] = $k . '=' . $v;
            } else {
                $encoded_params[] = $k . '=' . urlencode($v);
            }
        }

        $params_string = implode('&', $encoded_params);

        $url_hash = parse_url($url);
        $keys = array(
            $method,
            $url_hash['host'],
            $url_hash['path'],
            $params_string,
        );

        $sign_string = implode("\n", $keys);
        $sign = base64_encode(hash_hmac('sha256', $sign_string, $client_key, true));

        return $sign;
    }
}
