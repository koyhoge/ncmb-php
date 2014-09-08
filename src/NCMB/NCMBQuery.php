<?php
namespace NCMB;

use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;

// @see https://gist.github.com/Dynom/5866837
// @see https://gist.github.com/kaz29/7380772

class NCMBQuery
{
    private $className;

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function find($queries = array())
    {
        $url = sprintf(
            '%s/%s/%s/%s',
            NCMB::get('apiUrl'),
            NCMB::get('apiVersion'),
            'classes',
            $this->className
        );

        $timestamp = $this->timestamp();
        $sign = $this->sign($url, 'GET', $queries, $timestamp);

        $applicationKey = NCMB::get('appId');

        $res = null;
        try {
            $client = new GuzzleHttp\Client();
            $res = $client->get($url,
                array(
                    'headers' => array(
                        'X-NCMB-Application-Key' => $applicationKey,
                        'X-NCMB-Signature' => $sign,
                        'X-NCMB-Timestamp' => $timestamp,
                    ),
                    'query' => $queries
                )
            );
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            }
        }

        return $res;
    }

    public function findOneById($objectId)
    {
        $url = sprintf(
            '%s/%s/%s/%s/%s',
            NCMB::get('apiUrl'),
            NCMB::get('apiVersion'),
            'classes',
            $this->className,
            $objectId
        );

        $timestamp = $this->timestamp();
        $sign = $this->sign($url, 'GET', array(), $timestamp);

        $applicationKey = NCMB::get('appId');

        $res = null;
        try {
            $client = new GuzzleHttp\Client();
            $res = $client->get($url,
                array(
                    'headers' => array(
                        'X-NCMB-Application-Key' => $applicationKey,
                        'X-NCMB-Signature' => $sign,
                        'X-NCMB-Timestamp' => $timestamp,
                    ),
                )
            );
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $res = $e->getResponse();
            }
        }

        return $res;
    }

    private function timestamp()
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

    private function sign($url, $method, $params, $timestamp)
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
