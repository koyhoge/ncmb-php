<?php

namespace NCMB\Test;

use NCMB\NCMB;
use NCMB\NCMBUser;

class NCMBUserTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        NCMB::init(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
    }

    /**
     * @test
     */
    public function saveができる()
    {
        $user = new NCMBUser();
        $uniqid = uniqid('test');

        $user->put('userName', $uniqid);
        $user->put('password', 'password');

        $res = $user->save();
        $data = $res->json();

        $this->assertEquals(201, $res->getStatusCode());
        $this->assertTrue(isset($data['objectId']));
        $this->assertTrue(isset($data['createDate']));
        $this->assertTrue(!empty($data['sessionToken']));
        $this->assertEquals($uniqid, $data['userName']);
    }
}
