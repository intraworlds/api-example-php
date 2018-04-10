<?php

namespace IW\API;

interface Api_adapter
{
    const HTTP_METHOD_GET = "METHOD_GET";
    const HTTP_METHOD_POST = "METHOD_POST";
    const HTTP_METHOD_PUT = "METHOD_PUT";
    const HTTP_METHOD_DELETE = "METHOD_DELETE";
	/*
    * Method that fetches response from intraworlds REST API from payload
    * and chosen method.
    * returns response in JSON if there was no error
    * throws an Exception when an error occurs
	*/
    public function send_request($url, $payload, $method):string;
}

/**
 * Exception message contains json with response_code, response_detail, response_headers
 */
class Api_Exception extends \Exception {
    public function __construct(int $response_code, string $response_detail, array $response_headers) {
        $json = json_encode([
            'response_detail' => $response_detail,
            'response_headers' => $response_headers
        ]);

        parent::__construct($json, $response_code);
    }

}