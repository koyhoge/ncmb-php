<?php

namespace NCMB;

class NCMB
{
    private static $params = array(
        'apiUrl' => 'https://mb.api.cloud.nifty.com',
        'apiVersion' => '2013-09-01',
    );

    public static function init($appId, $clientKey)
    {
        self::$params['applicationKey'] = $appId;
        self::$params['clientKey'] = $clientKey;
    }

    public static function get($key)
    {
        return self::$params[$key];
    }

    public static function createClient()
    {
        $client = NCMBAPIClient::create(
            self::get('applicationKey'),
            self::get('clientKey')
        );

        return $client;
    }
}
