<?php

namespace NCMB\Test;

use NCMB\NCMB;

class NCMBTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function initは初期化する()
    {
        NCMB::init('test app id', 'test client key');

        $this->assertEquals('https://mb.api.cloud.nifty.com', NCMB::get('apiUrl'));
        $this->assertEquals('2013-09-01', NCMB::get('apiVersion'));
        $this->assertEquals('test app id', NCMB::get('appId'));
        $this->assertEquals('test client key', NCMB::get('clientKey'));
    }
}
