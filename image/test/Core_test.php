<?php

use IW\API\Core;
use IW\API\Api_adapter;
use IW\API\Api_Exception;
use PHPUnit\Framework\TestCase;

final class Core_test extends TestCase{
	private $core;

	protected function setUp(){
        $this->core = new Core(
            new class() implements Api_adapter {
                public function send_request($url, $payload, $method):string {
                    if ($url == 'good') {
                        return '{"api": "good"}';
                    } else {
                        throw new Api_Exception(500, 'bad', ['header1' => 'detail']);
                    }
                }
            }
        );

	}

	public function test_getResponse_success(){
		$decoded_response = json_decode($this->core->get_response("good", "", ""), true);

		$response = $decoded_response["response"];
		$time = $decoded_response["time"];
		$response_json = json_encode($response);

        $this->assertEquals('{"response_code":200,"response_body":{"api":"good"}}', $response_json);
        $this->assertInternalType("numeric", $time);
	}

    public function test_getResponse_failure(){
        $decoded_response = json_decode($this->core->get_response("bad", "", ""), true);

        $response = $decoded_response["response"];
        $time = $decoded_response["time"];
        $response_json = json_encode($response);

        $this->assertEquals('{"response_code":500,"response_detail":"bad","response_headers":{"header1":"detail"}}', $response_json);
        $this->assertInternalType("numeric", $time);
    }
}
