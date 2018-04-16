<?php

namespace IW\API\Api_Adapter;

/**
 * Exception message contains json with response_code, response_detail, response_headers
 */
class Exception extends \Exception {
    public function __construct(int $response_code, string $response_detail, array $response_headers) {
        $json = json_encode([
            'response_detail' => $response_detail,
            'response_headers' => $response_headers
        ]);

        parent::__construct($json, $response_code);
    }

}