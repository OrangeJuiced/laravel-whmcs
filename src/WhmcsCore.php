<?php

namespace WHMCS;

use GuzzleHttp\Client;
use WHMCS\Error\WHMCSConnectionException;
use WHMCS\Error\WHMCSResultException;

class WhmcsCore {

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var string
     */
    protected $response_type;

    /**
     * Instantiate a new instance
     *
     * @return void
     */
    public function __construct()
    {
        $this->identifier     = config('whmcs.identifier');
        $this->secret         = config('whmcs.secret');
        $this->response_type  = strtolower(config('whmcs.response_type'));

        $this->client = new Client([
            'base_uri'  => config('whmcs.url'),
            'timeout'   => config('whmcs.timeout'),
            'headers'   => ['Accept' => 'application/json']
        ]);
    }

    /**
     * Respond to a WHMCS request
     *
     * @param type
     * @return array
     */
    public function submitRequest($data, $requiresuccess = true)
    {
        try {
            $data = $this->addNecessaryParams($data);
            $response = $this->client->request('POST', '', [
                'query' => $data,
                'http_errors' => true,
                'verify' => false
            ]);

            $response = $this->handleResponse($response);

           // If the response MUST have a success result, we will throw an exception.
            if ($requiresuccess)
            {
                if ($response["result"] !== "success")
                {
                    if ($response["result"] == "error")
                    {
                        throw new WHMCSResultException("Request failed with error: " . $response["message"]);
                    }
                    throw new WHMCSResultException("Request failed, no error message found. Result was " . $response["result"]);
                }
                return $response;
            }
        }catch(\Exception $e)
        {
            throw new WHMCSConnectionException($e->getMessage());
        }
    }

    /**
     * Adds the WHMCS secret, identifier and response to the request
     *
     * @param array $params
     * @return array
     */
    protected function addNecessaryParams($params)
    {
        $params['identifier']       = $this->identifier;
        $params['secret']           = $this->secret;
        $params['responsetype']     = $this->response_type;

        return $params;
    }

    /**
     * Formats the response based on the set response_type
     *
     * @param array $response
     * @return array
     */
    protected function handleResponse($response)
    {
        if($this->response_type === 'json')
            return json_decode($response->getBody(), true);

        return simplexml_load_string($response->getBody());
    }

}
