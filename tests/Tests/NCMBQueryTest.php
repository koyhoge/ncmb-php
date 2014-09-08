<?php

namespace NCMB\Test;

use NCMB\NCMB;
use NCMB\NCMBQuery;

class NCMBQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        NCMB::init(TEST_APPLICATION_ID, TEST_CLIENT_KEY);
    }

    /**
     * @test
     */
    public function findは一覧を取得できる()
    {
        $query = new NCMBQuery('TestClass');
        $result = $query->find();
        $data = $result->json();

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertTrue(is_array($data['results']));
    }

    /**
     * @test
     */
    public function findはクエリを指定して検索できる()
    {
        $query = new NCMBQuery('TestClass');

        $queries = array(
            'where' => json_encode(array(
                'createDate' => array(
                    '$gte' => array(
                        '__type' => 'Date',
                        //'iso' => '2014-01-01T00:00:00.000Z'
                        //'iso' => '2014-08-27T04:03:36.167Z'
                        'iso' => '2014-08-27T13:52:57.663Z',
                    )
                )
            )),
            'limit' => 10,
            'order' => 'createDate',
        );

        $result = $query->find($queries);
        $data = $result->json();

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertTrue(is_array($data['results']));
    }
}
