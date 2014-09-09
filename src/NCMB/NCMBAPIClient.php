<?php
namespace NCMB;

use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;

// @see https://gist.github.com/Dynom/5866837
// @see https://gist.github.com/kaz29/7380772

class NCMBAPIClient
{
    private $client;

    public function __construct(GuzzleHttp\ClientInterface $client = null)
    {
        $this->client = $client ? $client : new GuzzleHttp\Client();
    }

    public function getClient()
    {
        return $this->client;
    }

    public function get($path, $options = array())
    {
        return $this->send('GET', $path, $options);
    }

    public function post($path, array $options = array())
    {
        return $this->send('POST', $path, $options);
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
        return sprintf('%s/%s%s', NCMB::get('apiUrl'), NCMB::get('apiVersion'), $path);
    }

    protected function createDefaultHeaders($method, $url, array $options = array())
    {
        $applicationKey = NCMB::get('appId');
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
        $application_key = NCMB::get('appId');
        $client_key = NCMB::get('clientKey');

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
