<?php
namespace IW\API\Api_Adapter\OAuth;

class Token_Storage_In_Session {
    private $unique_access_token_key;

    public function __construct($base_url, $consumer_key) {
        $this->unique_access_token_key = 'OAUTH_TOKEN_' . $base_url . $consumer_key;
    }

    public function store_token($token) {
        $_SESSION[$this->unique_access_token_key] = $token;
    }

    public function retrieve_token() {
        return $_SESSION[$this->unique_access_token_key] ?? null;
    }

}