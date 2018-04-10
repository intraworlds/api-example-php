<?php

$loader = require __DIR__ .'/../vendor/autoload.php';

use IW\API\Core;
use IW\API\OAuth_adapter;

/*
* script for handling user input(url, consumer key, consumer secret, method, payload)
* and calling functions from Core.php.
*/

$url = $_POST["url"];
$username = $_POST["username"];
$password = $_POST["password"];
$method = $_POST["method"];
$payload = $_POST["payload"];

$base_url = explode("/rest/", $url)[0];

$adapter = new OAuth_adapter($base_url, $username, $password);
$core = new Core($adapter);

echo $core->get_response($url, $method, $payload);
