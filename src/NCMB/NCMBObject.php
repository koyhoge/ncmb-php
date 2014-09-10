<?php
namespace NCMB;

use NCMB\NCMBAPIClient;

class NCMBObject
{
    private $className;
    private $data = array();

    public function __construct($className)
    {
        $this->className = $className;
    }

    public function put($key, $val)
    {
        $this->data[$key] = $val;
    }

    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    protected function getApiPath()
    {
        return '/classes/' . $this->className;
    }

    public function save()
    {
        $client = new NCMBAPIClient();
        $path = $this->getApiPath();
        $options = array(
            'json' => $this->data,
        );

        // TODO: put対応

        return $client->post($path, $options);
    }
}
