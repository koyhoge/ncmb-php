<?php

namespace NCMB;

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
}
