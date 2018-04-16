<?php
namespace IW\API;
/**
 * Interface for adapter for communicating with api.
 */
interface ApiAdapter
{
    const HTTP_METHOD_GET = "METHOD_GET";
    const HTTP_METHOD_POST = "METHOD_POST";
    const HTTP_METHOD_PUT = "METHOD_PUT";
    const HTTP_METHOD_DELETE = "METHOD_DELETE";
    /**
     * Method that fetches response from intraworlds REST API from payload
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
    public function sendRequest($url, $payload, $method):string;
}

