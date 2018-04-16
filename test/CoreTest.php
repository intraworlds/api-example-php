<?php

use IW\API\Core;
use IW\API\ApiAdapter;
use PHPUnit\Framework\TestCase;
/**
 * Class for testing Core.php
 */
final class CoreTest extends TestCase
{
    private $_core;
    /**
     * Set up function for creating Core instance
     * 
     * @return void
     */
    protected function setUp()
    {
        $this->_core = new Core(
            new class() implements ApiAdapter {
                /**
                  * Method that fetches response from 
                  * intraworlds REST API from payload
                  * and chosen method.
                  * returns response in JSON if there was no error
                  * throws an Exception when an error occurs
                  *
                  * @param string $url     url for api request
                  * @param string $payload payload for api request
                  * @param string $method  method for api request
                  *
                  * @return string representation of response
                  */
                public function sendRequest($url, $payload, $method):string 
                {
                    if ($url == 'good') {
                        return '{"api": "good"}';
                    } else {
                        throw new ApiAdapter\Exception(
                            500, 'bad', ['header1' => 'detail']
                        );
                    }
                }
            }
        );

    }
    /**
     * Test for successful response
     *
     * @return void
     */
    public function testGetResponseSuccess()
    {
        $decodedResponse = json_decode(
            $this->_core->getResponse(
                "good", 
                "", ""
            ), true
        );

        $response = $decodedResponse["response"];
        $time = $decodedResponse["time"];
        $responseJson = json_encode($response);

        $this->assertEquals(
            '{"responseCode":200,"responseBody":{"api":"good"}}',
            $responseJson
        );
        $this->assertInternalType("numeric", $time);
    }
    /**
     * Test for failure
     *
     * @return void
     */
    public function testGetResponseFailure()
    {
        $decodedResponse = json_decode(
            $this->_core->getResponse(
                "bad", 
                "", ""
            ), true
        );

        $response = $decodedResponse["response"];
        $time = $decodedResponse["time"];
        $responseJson = json_encode($response);

        $this->assertEquals(
            '{"responseCode":500,"responseDetail":"bad",'+
            '"responseHeaders":{"header1":"detail"}}', $responseJson
        );
        $this->assertInternalType("numeric", $time);
    }
}
