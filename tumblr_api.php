<?php

require_once __DIR__ . '/vendor/autoload.php';

session_start();

$endpoint = 'https://api.tumblr.com/v2/blog/peter279k.tumblr.com/info';
$client = new \GuzzleHttp\Client;

$response = $client->request('GET', $endpoint, [
    'headers' => [
        'Authorization' => 'Bearer ' . $_SESSION['access_token'],
    ],
]);

var_dump((string) $response->getBody());
