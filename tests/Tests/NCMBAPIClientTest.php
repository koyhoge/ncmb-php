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
        $client = NCMBAPIClient::create(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
        $res = $client->get('/classes/TestClass');
        $data = $res->json();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertTrue(is_array($data['results']));
    }

    /**
     * @test
     */
    public function postできる()
    {
        $client = NCMBAPIClient::create(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
        $res = $client->post('/classes/TestClass', array(
            'json' => array('message' => 'test'),
        ));
        $data = $res->json();

        $this->assertEquals(201, $res->getStatusCode());
        $this->assertTrue(isset($data['objectId']));
    }
}
