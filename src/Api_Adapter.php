<?php

namespace IW\API;

interface Api_Adapter
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

