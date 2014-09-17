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
        $uniqid = uniqid('test', true);

        $user->set('userName', $uniqid);
        $user->set('password', 'password');

        $res = $user->save();
        $data = $res->json();

        $this->assertEquals(201, $res->getStatusCode());
        $this->assertTrue(isset($data['objectId']));
        $this->assertTrue(isset($data['createDate']));
        $this->assertTrue(!empty($data['sessionToken']));
        $this->assertEquals($uniqid, $data['userName']);
    }

    /**
     * @test
     */
    public function sessionTokenを設定取得できる()
    {
        $user = new NCMBUser();
        $user->setSessionToken('aaa');

        $this->assertEquals('aaa', $user->getSessionToken());
    }

    /**
     * @test
     */
    public function loginができる()
    {
        $user = new NCMBUser();
        $uniqid = uniqid('test', true);
        $password = 'password';

        $user->set('userName', $uniqid);
        $user->set('password', $password);
        $res = $user->save();

        $this->assertEquals(201, $res->getStatusCode());

        $res = $user->login($uniqid, $password);
        $data = $res->json();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertEquals($uniqid, $data['userName']);
        $this->assertTrue(!empty($data['sessionToken']));

        $this->assertEquals($data['sessionToken'], $user->getSessionToken());
    }

    /**
     * @test
     */
    public function logoutできる()
    {
        $user = new NCMBUser();
        $uniqid = uniqid('test', true);
        $password = 'password';

        $user->set('userName', $uniqid);
        $user->set('password', $password);
        $res = $user->save();

        $this->assertEquals(201, $res->getStatusCode());

        $res = $user->login($uniqid, $password);
        $this->assertEquals(200, $res->getStatusCode());
        $this->assertTrue(!empty($user->getSessionToken()));

        $res = $user->logout();
        $data = $res->json();

        $this->assertEquals(200, $res->getStatusCode());
        $this->assertEquals(null, $data);
    }

    /**
     * @test
     * @expectedException GuzzleHttp\Exception\ClientException
     */
    public function 同userでのloginすると旧sessionTokenは使えなくなる()
    {
        $user = new NCMBUser();
        $uniqid = uniqid('test', true);
        $password = 'password';
        $user->set('userName', $uniqid);
        $user->set('password', $password);
        $res = $user->save();

        $this->assertEquals(201, $res->getStatusCode());
        $res1 = $user->login($uniqid, $password);
        $sessionToken1 = $user->getSessionToken();

        $user2 = new NCMBUser();
        $res2 = $user2->login($uniqid, $password);
        $sessionToken2 = $user2->getSessionToken();

        $this->assertNotEquals($sessionToken1, $sessionToken2);
        $user->logout();
    }
}
