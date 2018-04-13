<?php

namespace IW\API;

session_start();

$loader = require __DIR__ .'/../vendor/autoload.php';


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

$token_storage = new Api_Adapter\OAuth\Token_Storage_In_Session($base_url, $username);
$adapter = new Api_Adapter\OAuth($base_url, $username, $password, $token_storage);
$core = new Core($adapter);

echo $core->get_response($url, $method, $payload);
