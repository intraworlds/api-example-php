<?php
namespace IW\API\Api_Adapter;

use IW\API\Api_Adapter;

/*
* Class for comunicating with REST API of IntraWorlds.
*/
class OAuth implements Api_Adapter{

    private $consumer_key;
    private $consumer_secret;
    private $oauth;
    private $header = array('Accept' => 'application/json', 'Content-Type' => 'application/json');
    private $base_url;
    private $token_storage;


    /*
    * Constructor that sets url of user, REST API url, consumer key and
    * consumer secret and creates OAuth instance from consumer key and
    * consumer secret.
    */
    public function __construct($base_url, $consumer_key, $consumer_secret, $token_storage) {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->base_url = $base_url;
        $this->token_storage = $token_storage;

        $this->oauth = new \OAuth($this->consumer_key, $this->consumer_secret);
    }

    /*
    * Method that fetches response from intraworlds REST API from payload
    * and chosen method.
    * returns response in json if there was no error
    * returns error information if something went wrong
    */
    public function send_request($url, $payload, $method):string{
        return $this->send_request_inner($url, $payload, $method);
    }

    /*
    * Initializes OAuth access token.
    *
    * If called for the first time or if the access token need renewal, it does the full authentication.
    */
    private function init_access_token($force_access_token_renewal = false) {
        if ($force_access_token_renewal || ($accessToken = $this->token_storage->retrieve_token()) == null) {

            $reqUrl = $this->base_url.'/remoteapi/oauth/request-token?auth=1';
            $accUrl = $this->base_url.'/remoteapi/oauth/access-token';

            $requestToken = $this->oauth->getRequestToken($reqUrl);
            $this->oauth->setToken($requestToken['oauth_token'], $requestToken['oauth_token_secret']);

            $accessToken = $this->oauth->getAccessToken($accUrl);
            $this->token_storage->store_token($accessToken);
        }

        $this->oauth->setToken($accessToken['oauth_token'], $accessToken['oauth_token_secret']);
    }

    private function send_request_inner($url, $payload, $method, $force_access_token_renewal = false) {
        try {
            $this->init_access_token($force_access_token_renewal);

            switch ($method) {
                case Api_Adapter::HTTP_METHOD_GET:
                    $payload = '';
                    $oauth_method = OAUTH_HTTP_METHOD_GET;
                    break;
                case Api_Adapter::HTTP_METHOD_POST:
                    $oauth_method = OAUTH_HTTP_METHOD_POST;
                    break;
                case Api_Adapter::HTTP_METHOD_PUT:
                    $oauth_method = OAUTH_HTTP_METHOD_PUT;
                    break;
                case Api_Adapter::HTTP_METHOD_DELETE:
                    $oauth_method = OAUTH_HTTP_METHOD_DELETE;
                    break;
                default:
                    throw new IllegalArgumentException();
            }

            $this->oauth->fetch($url, $payload, $oauth_method, $this->header);

            return $this->oauth->getLastResponse();
        } catch (\OAuthException $e) {
            if ($e->getCode() == '401' && !$force_access_token_renewal) {
                // the access token must have expired => try it once again
                return $this->send_request_inner($url, $payload, $method, true);
            }else{
                $info = $this->oauth->getLastResponseInfo();

                throw new Exception($e->getCode(), $e->getMessage(), $info);
            }

        }
    }
}
