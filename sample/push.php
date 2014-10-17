<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use NCMB\NCMB;
use NCMB\NCMBAPIClient;

NCMB::init(
    'appkey',
    'clientKey',
);

for ($i = 0; $i < 2000; $i++) {
    $devices[] = uniqid() . uniqid() . uniqid();
}

$params = [
    'message' => 'aaa',
    'deliveryTime' => [
        '__type' => 'Date',
        'iso' => '2014-09-20T01:42:00.000Z'
    ],
    'immediateDeliveryFlag' => false,
    'searchCondition' => [
        'deviceToken' => [
            '$in' => $devices
        ]
    ],
    'target' => ['android'],
    'acl' => null,
    'deliveryExpirationDate' => null,
    'deliveryExpirationTime' => '10 day',
    'action' => ''
];

print_r($params);

$client = NCMB::createClient();
try {
    $res = $client->post('/push', array(
        'json' => $params
    ));
} catch (Exception $e) {
    $res = $e->getResponse();
}

$data = $res->json();
print_r($data);
