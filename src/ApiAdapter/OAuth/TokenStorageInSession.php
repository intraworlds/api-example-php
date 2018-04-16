<?php
namespace IW\API\ApiAdapter\OAuth;
/**
 * Class for storing access token from credentials.
 */
class TokenStorageInSession
{
    private $_uniqueAccessTokenKey;
    /**
     * Constructor for setting token from user's url and consumer key.
     *
     * @param string $baseUrl     user's url
     * @param string $consumerKey user's consumer key
     */
    public function __construct($baseUrl, $consumerKey) 
    {
        $this->_uniqueAccessTokenKey = 'OAUTH_TOKEN_' . $baseUrl . $consumerKey;
    }
    /**
     * Method for storing token in $_SESSION.
     *
     * @param string $token token to store
     *
     * @return void
     */
    public function storeToken($token) 
    {
        $_SESSION[$this->_uniqueAccessTokenKey] = $token;
    }
    /**
     * Method for retrieving token from $_SESSION.
     *
     * @return stored token
     */
    public function retrieveToken() 
    {
        return $_SESSION[$this->_uniqueAccessTokenKey] ?? null;
    }

}