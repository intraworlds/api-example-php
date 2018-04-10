<?php
namespace IW\API;

class Core{
	private $api_adapter;

	public function __construct(Api_adapter $api_adapter){
		$this->api_adapter = $api_adapter;
	}

	public function get_response($url, $method, $payload){
		$start_time = microtime(true);

		try {
			$json = $this->api_adapter->send_request($url, $payload, $method);
			$response = '{"response_body":'.$json.'}';
			$response_code = 200;
		} catch (Api_Exception $e){
			$response = $e->getMessage();
			$response_code = $e->getCode();
		}

		$total_time = microtime(true) - $start_time;

		return $this->format_response($total_time, $response, $response_code);
	}

	private function format_response($total_time, $response, $response_code){
		$decoded_response = json_decode($response);
		$time_and_response = [
			'time' => $total_time,
			'response' => [
				'response_code' => $response_code,
			]
		];

		foreach ($decoded_response as $key => $value) {
			$time_and_response['response'][$key] = $value;
		}

		return json_encode($time_and_response);
	}

}
