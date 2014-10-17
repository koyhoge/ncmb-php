<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use NCMB\NCMB;
use NCMB\NCMBAPIClient;

NCMB::init(
    'appKey',
    'clientKey',
);

define('TEST_MAIL_ADDRESS', 'aaa@localhost');

main();

function main() {
    $params = [
        'userName' => TEST_MAIL_ADDRESS,
        'password' => 'pass',
        'mailAddress' => TEST_MAIL_ADDRESS,
        'tmpMailAddress' => TEST_MAIL_ADDRESS,
        'field1' => 'aaa',
        'field2' => 'bbb'
    ];

    #$data1 = createUser($params);
    #print_r($data1);

    $data1 = [
        'objectId' => 'gXXrF1GH3QdtljiX',
        'sessionToken' => 'P9yb4iMMX7lGZSYlTQCz5lHoa',
    ];
    $params = [
        '_mailAddressConfirm' => 'aa',
        'mailAddress' => TEST_MAIL_ADDRESS,
        #'mailAddressConfirm' => false
    ];
    $data2 = editUser($data1['objectId'], $data1['sessionToken'], $params);
    print_r($data2);

    #$data3 = getUser($data1['objectId'], $data1['sessionToken']);
    #print_r($data3);

    #$data4 = login($params['userName'], $params['password']);
    #print_R($data4);
    
}


function createUser($params) {
    $client = NCMB::createClient();
    $res = $client->post('/users', array('json' => $params));
    return $res->json();
}

function getUser($objectId, $sessionToken) {
    $client = NCMB::createClient();
    $res = $client->get('/users/' . $objectId, array(
        'headers' => array(
            'X-NCMB-Apps-Session-Token' => $sessionToken
        ),
    ));
    return $res->json();
}

// edit user
function editUser($objectId, $sessionToken, $data) {
    $client = NCMB::createClient();
    $res = $client->put('/users/' . $objectId, array(
        'json' => $data,
        'headers' => array(
            'X-NCMB-Apps-Session-Token' => $sessionToken
        ),
    ));
    return $res->json();
}


// login
function login($userName, $password) {
    $params = [
        'userName' => $userName,
        'password' => $password,
    ];
    $client = NCMB::createClient();
    $res = $client->get('/login', array('query' => $params));
    return $res->json();
}


// password reset
function passwordReset($mailAddress) {
    $params = ['mailAddress' => $mailAddress];
    $client = NCMB::createClient();
    $res = $client->post('/requestPasswordReset', array('json' => $params));
    return $res->json();
}
