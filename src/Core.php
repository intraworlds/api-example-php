<?php
namespace IW\API;
/**
 * Purpose of class Core is communicating with api adapter.
 */
class Core
{
    private $_apiAdapter;
    /**
     * Constructor that sets api Adapter.
     *
     * @param ApiAdapter $apiAdapter adapter, that can communicate with api
     */
    public function __construct(ApiAdapter $apiAdapter)
    {
        $this->_apiAdapter = $apiAdapter;
    }
    /**
     * Function getResponse gets formatted response from api adapter.
     *
     * @param string $url     url of api
     * @param string $method  method we are using
     * @param string $payload payload provided for api
     *
     * @return json formatted response with time, response and response code
     */
    public function getResponse($url, $method, $payload)
    {
        $startTime = microtime(true);

        try 
        {
            $json = $this->_apiAdapter->sendRequest($url, $payload, $method);
            $response = '{"responseBody":'.$json.'}';
            $responseCode = 200;
        } catch (ApiAdapter\Exception $e)
        {
            $response = $e->getMessage();
            $responseCode = $e->getCode();
        }

        $totalTime = microtime(true) - $startTime;

        return $this->_formatResponse($totalTime, $response, $responseCode);
    }
    /**
     * Function _formatResponse formats response, time and response code into json.
     *
     * @param float  $totalTime    meaured time the response took to arrive
     * @param string $response     response in json
     * @param int    $responseCode code of response
     *
     * @return json formatted response with time, response and response code
     */
    private function _formatResponse($totalTime, $response, $responseCode)
    {
        $decodedResponse = json_decode($response);
        $tomeAndResponse = [
            'time' => $totalTime,
            'response' => [
                'responseCode' => $responseCode,
            ]
        ];

        foreach ($decodedResponse as $key => $value) {
            $tomeAndResponse['response'][$key] = $value;
        }

        return json_encode($tomeAndResponse);
    }

}
