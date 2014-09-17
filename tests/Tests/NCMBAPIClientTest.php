<?php

namespace NCMB\Test;

use NCMB\NCMB;
use NCMB\NCMBAPIClient;

class NCMBAPIClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getできる()
    {

        $path = '/classes/TestClass';
        $queries = array();

        $client = NCMBAPIClient::create(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
        $res = $client->get($path, array(
            'query' => $queries
        ));
        $data = $res->json();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertTrue(is_array($data['results']));
    }

    /**
     * @test
     */
    public function postできる()
    {
        $path = '/classes/TestClass';

        $client = NCMBAPIClient::create(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
        $res = $client->post($path, array(
            'json' => array('message' => 'test'),
        ));
        $data = $res->json();

        $this->assertEquals(201, $res->getStatusCode());
        $this->assertTrue(isset($data['objectId']));
    }
}
