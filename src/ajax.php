<?php
/**
 * Purpose of this script is getting arguments 
 * from $_POST and passing arguments to api adapter 
 * and Core.php
 */
namespace IW\API;

session_start();

$loader = include __DIR__ .'/../vendor/autoload.php';


/**
 * Script for handling user input(url, consumer key, 
 * consumer secret, method, payload)
 * and calling functions from Core.php.
 */

$url = $_POST["url"];
$username = $_POST["username"];
$password = $_POST["password"];
$method = $_POST["method"];
$payload = $_POST["payload"];

$baseUrl = explode("/rest/", $url)[0];

$tokenStorage = new ApiAdapter\OAuth\TokenStorageInSession($baseUrl, $username);
$adapter = new ApiAdapter\OAuth($baseUrl, $username, $password, $tokenStorage);
$core = new Core($adapter);

echo $core->getResponse($url, $method, $payload);
