<?php
namespace NCMB;

use NCMB\NCMBAPIClient;
use NCMB\NCMBObject;

class NCMBUser extends NCMBObject
{
    private $sessionToken = null;

    public function __construct()
    {
        parent::__construct('user');
    }

    protected function getApiPath()
    {
        return '/users';
    }

    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
    }

    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    public function login($userName, $password)
    {
        $path = '/login';
        $client = new NCMBAPIClient();
        $options = array(
            'query' => array(
                'userName' => $userName,
                'password' => $password,
            ),
        );

        $res = $client->get($path, $options);

        if ($res->getStatusCode() == 200) {
            $data = $res->json();
            $this->setSessionToken($data['sessionToken']);
        }

        return $res;
    }

    public function logout()
    {
        $path = '/logout';
        $client = new NCMBAPIClient();
        $options = array();
        if ($sessionToken = $this->getSessionToken()) {
            $options['headers'] = array(
                'X-NCMB-Apps-Session-Token' => $sessionToken,
            );
        }

        return $client->get($path, $options);
    }
}
