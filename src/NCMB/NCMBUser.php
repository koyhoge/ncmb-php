<?php
namespace NCMB;

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
        $options = array(
            'query' => array(
                'userName' => $userName,
                'password' => $password,
            ),
        );

        $client = NCMB::createClient();
        $res = $client->get('/login', $options);

        if ($res->getStatusCode() == 200) {
            $data = $res->json();
            $this->setSessionToken($data['sessionToken']);
        }

        return $res;
    }

    public function logout()
    {
        $options = array();
        if ($sessionToken = $this->getSessionToken()) {
            $options['headers'] = array(
                'X-NCMB-Apps-Session-Token' => $sessionToken,
            );
        }

        $client = NCMB::createClient();

        return $client->get('/logout', $options);
    }
}
