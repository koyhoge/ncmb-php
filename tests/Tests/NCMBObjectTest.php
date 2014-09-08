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
}
