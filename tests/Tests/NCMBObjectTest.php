<?php

namespace NCMB\Test;

use NCMB\NCMB;
use NCMB\NCMBObject;

class NCMBObjectTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        NCMB::init(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
    }

    /**
     * @test
     */
    public function putとgetができる()
    {
        $gameScore = new NCMBObject('gameScore');
        $gameScore->set('score', 1000);
        $gameScore->set('playerName', 'GuestUser');

        $this->assertEquals(1000, $gameScore->get('score'));
        $this->assertEquals('GuestUser', $gameScore->get('playerName'));
    }

    /**
     * @test
     */
    public function saveができる()
    {
        $gameScore = new NCMBObject('gameScore');
        $gameScore->set('score', 1000);
        $gameScore->set('playerName', 'GuestUser');
        $res = $gameScore->save();
        $data = $res->json();

        $this->assertEquals(201, $res->getStatusCode());
        $this->assertTrue(isset($data['objectId']));
        $this->assertTrue(isset($data['createDate']));
    }
}
