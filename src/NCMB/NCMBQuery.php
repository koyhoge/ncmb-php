<?php
namespace NCMB;

use NCMB\NCMBAPIClient;

class NCMBQuery
{
    private $className;

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function find($queries = array())
    {
        $path = sprintf(
            '/%s/%s',
            'classes',
            $this->className
        );

        $client = new NCMBAPIClient();

        return $client->get($path, array(
            'query' => $queries
        ));
    }

    public function findOneById($objectId)
    {
        $path = sprintf(
            '/%s/%s/%s',
            'classes',
            $this->className,
            $objectId
        );

        $client = new NCMBAPIClient();

        return $client->get($path);
    }
}
