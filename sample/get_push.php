<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use NCMB\NCMB;
use NCMB\NCMBAPIClient;

NCMB::init(
    'appkey',
    'clientKey',
);

$query = [
    'objectId' => '6pXHbaW1ioXwaWL2',
];


$client = new NCMBAPIClient();
try {
    $res = $client->get('/push', array(
        'query' => $query
    ));
} catch (Exception $e) {
    $res = $e->getResponse();
}

$data = $res->json();

print_r($data);
