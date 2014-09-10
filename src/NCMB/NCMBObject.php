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

    public function save()
    {
        $client = new NCMBAPIClient();
        $path = '/classes/' . $this->className;
        $options = array(
            'json' => $this->data,
        );

        // TODO: put対応

        return $client->post($path, $options);
    }
}
