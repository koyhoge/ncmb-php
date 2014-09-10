<?php

namespace NCMB\Test;

use NCMB\NCMBObject;

class NCMBObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function putとgetができる()
    {
        $gameScore = new NCMBObject('gameScore');
        $gameScore->put('score', 1000);
        $gameScore->put('playerName', 'GuestUser');

        $this->assertEquals(1000, $gameScore->get('score'));
        $this->assertEquals('GuestUser', $gameScore->get('playerName'));
    }

    /**
     * @test
     */
    public function saveができる()
    {
        $gameScore = new NCMBObject('gameScore');
        $gameScore->put('score', 1000);
        $gameScore->put('playerName', 'GuestUser');
        $res = $gameScore->save();
        $data = $res->json();

        $this->assertEquals(201, $res->getStatusCode());
        $this->assertTrue(isset($data['objectId']));
        $this->assertTrue(isset($data['createDate']));
    }
}
