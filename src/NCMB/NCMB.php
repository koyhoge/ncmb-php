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
        self::$params['appId'] = $appId;
        self::$params['clientKey'] = $clientKey;
    }

    public static function get($key)
    {
        return self::$params[$key];
    }
}
