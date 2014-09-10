<?php
namespace NCMB;

use NCMB\NCMBAPIClient;
use NCMB\NCMBObject;

class NCMBUser extends NCMBObject
{
    public function __construct()
    {
        parent::__construct('user');
    }

    protected function getApiPath()
    {
        return '/users';
    }

}
