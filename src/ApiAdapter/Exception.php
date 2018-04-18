<?php

namespace IW\API\ApiAdapter;

/**
 * Exception message contains json with responseCode, responseDetail, responseHeaders
 */
class Exception extends \Exception
{
    /**
     * Constructor for creating exception from responseCode, 
     * responseDetail, responseHeaders
     *
     * @param int    $responseCode    code of response
     * @param string $responseDetail  details of response
     * @param array  $responseHeaders response headers
     */
    public function __construct(int $responseCode, 
        string $responseDetail, array $responseHeaders
    ) { 
    
        $json = json_encode(
            [
            'responseDetail' => $responseDetail,
            'responseHeaders' => $responseHeaders
            ]
        );

        parent::__construct($json, $responseCode);
    }

}