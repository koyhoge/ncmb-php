<?php

namespace NCMB\Test;

use NCMB\NCMB;
use NCMB\NCMBAPIClient;

class NCMBAPIClientTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        NCMB::init(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
    }

    /**
     * @test
     */
    public function hoge()
    {

        $path = '/classes/TestClass';
        $queries = array();

        $client = new NCMBAPIClient();
        $res = $client->get($path, $queries);
        $data = $res->json();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertTrue(is_array($data['results']));
    }

}
