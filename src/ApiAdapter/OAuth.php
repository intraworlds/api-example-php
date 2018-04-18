<?php
namespace IW\API\ApiAdapter;

use IW\API\ApiAdapter;

/**
 * Class for comunicating with REST API of IntraWorlds.
 */
class OAuth implements ApiAdapter
{

    private $_consumerKey;
    private $_consumerSecret;
    private $_oauth;
    private $_header = array('Accept' => 'application/json', 
        'Content-Type' => 'application/json');
    private $_baseUrl;
    private $_tokenStorage;


    /**
     * Constructor that sets url of user, REST API url, consumer key and
     * consumer secret and creates OAuth instance from consumer key and
     * consumer secret.
     *
     * @param string                $baseUrl        url of user
     * @param string                $consumerKey    consumer key
     * @param string                $consumerSecret consumer secret
     * @param TokenStorageInSession $tokenStorage   storage for tokens
     */
    public function __construct($baseUrl, $consumerKey, 
        $consumerSecret, $tokenStorage
    ) {
        $this->_consumerKey = $consumerKey;
        $this->_consumerSecret = $consumerSecret;
        $this->_baseUrl = $baseUrl;
        $this->_tokenStorage = $tokenStorage;

        $this->_oauth = new \OAuth($this->_consumerKey, $this->_consumerSecret);
    }

    /**
     * Method that fetches response from intraworlds REST API from payload
     * and chosen method.
     * returns response in json if there was no error
     * returns error information if something went wrong
     *
     * @param string $url     url for api
     * @param string $payload payload for api
     * @param string $method  method for api
     *
     * @return response from api
     */
    public function sendRequest($url, $payload, $method):string
    {
        return $this->_sendRequestInner($url, $payload, $method);
    }

    /**
     * Initializes OAuth access token.
     *
     * If called for the first time or if the access token need renewal, 
     * it does the full authentication.
     *
     * @param boolean $forceAccessTokenRenewal boolean, which tells 
     *                                         if it is necessary to renew 
     *                                         access token
     *
     * @return void
     */
    private function _initAccessToken($forceAccessTokenRenewal = false) 
    {
        if ($forceAccessTokenRenewal 
            || ($accessToken = $this->_tokenStorage->retrieveToken()) == null
        ) {

            $reqUrl = $this->_baseUrl.'/remoteapi/oauth/request-token?auth=1';
            $accUrl = $this->_baseUrl.'/remoteapi/oauth/access-token';

            $requestToken = $this->_oauth->getRequestToken($reqUrl);
            $this->_oauth->setToken(
                $requestToken['oauth_token'], 
                $requestToken['oauth_token_secret']
            );

            $accessToken = $this->_oauth->getAccessToken($accUrl);
            $this->_tokenStorage->storeToken($accessToken);
        }

        $this->_oauth->setToken(
            $accessToken['oauth_token'], 
            $accessToken['oauth_token_secret']
        );
    }
    /**
     * Method that fetches response from intraworlds REST API from payload
     * and chosen method.
     * returns response in json if there was no error
     * returns error information if something went wrong
     * 
     * @param string  $url                     url for api
     * @param string  $payload                 payload for api
     * @param string  $method                  method for api
     * @param boolean $forceAccessTokenRenewal boolean for 
     *                                         renewing access token renewal
     *
     * @return last response from api
     */
    private function _sendRequestInner($url, $payload, $method, 
        $forceAccessTokenRenewal = false
    ) { 
    
        try 
        {
            $this->_initAccessToken($forceAccessTokenRenewal);

            switch ($method) 
            {
            case ApiAdapter::HTTP_METHOD_GET:
                $payload = '';
                $oauthMethod = OAUTH_HTTP_METHOD_GET;
                break;
            case ApiAdapter::HTTP_METHOD_POST:
                $oauthMethod = OAUTH_HTTP_METHOD_POST;
                break;
            case ApiAdapter::HTTP_METHOD_PUT:
                $oauthMethod = OAUTH_HTTP_METHOD_PUT;
                break;
            case ApiAdapter::HTTP_METHOD_DELETE:
                $oauthMethod = OAUTH_HTTP_METHOD_DELETE;
                break;
            default:
                throw new IllegalArgumentException();
            }

            $this->_oauth->fetch($url, $payload, $oauthMethod, $this->_header);

            return $this->_oauth->getLastResponse();
        } 
        catch (\OAuthException $e) 
        {
            if ($e->getCode() == '401' && !$forceAccessTokenRenewal) {
                // the access token must have expired => try it once again
                return $this->_sendRequestInner($url, $payload, $method, true);
            } else {
                $info = $this->_oauth->getLastResponseInfo();

                throw new Exception($e->getCode(), $e->getMessage(), $info);
            }

        }
    }
}
