<?php

require_once __DIR__ . '/vendor/autoload.php';
$keys = require_once __DIR__ . '/tumblr_keys.php';

session_start();

$state = 'THIS_IS_UNIQUE_STATE';

if (empty($_GET['code'])) {
    $uri = 'https://www.tumblr.com/oauth2/authorize';
    $params = [
        'client_id' => $keys['consumer_key'],
        'response_type' => 'code',
        'scope' => 'basic',
        'state' => $state,
        'redirect_uri' => 'http://localhost:5050/tumblr_client.php',
    ];
    $uri .= '?' . http_build_query($params);
    header('Location: ' . $uri);

    exit();
}

$endpoint = 'https://api.tumblr.com/v2/oauth2/token';
$code = $_GET['code'];

if ($state !== $_GET['state']) {
    echo 'CSRF Detected!';
    exit();
}

$client = new \GuzzleHttp\Client();
$response = $client->request('POST', $endpoint, [
    'form_params' => [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'client_id' => $keys['consumer_key'],
        'client_secret' => $keys['secret_key'],
        'redirect_uri' => 'http://localhost:5050/tumblr_client.php',
    ],
]);

$jsonString = (string) $response->getBody();
$jsonArr = json_decode($jsonString, true);

$_SESSION['access_token'] = $jsonArr['access_token'];
$_SESSION['expire_in'] = $jsonArr['expire_in'];

echo 'The access_token is saved in the session.';
