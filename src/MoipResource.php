<?php

namespace Softpampa\Moip;

use stdClass;
use JsonSerializable;

abstract class MoipResource implements JsonSerializable {

    /**
     * @var  MoipHttpClient  $httpClient  Moip HTTP Client
     */
    protected $httpClient;

    /**
     * @var  MoipHttpResponse  $httpResponse  Moip HTTP Response
     */
    protected $httpResponse;

    /**
     * @var  stdClass  $data  Resource
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param  MoipHttpClient  $httpClient  HTTP Client
     */
    public function __construct(MoipHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->httpClient->setPath($this->path);
        $this->data = new stdClass;
    }

    /**
     * Get MoipHttpResponse
     *
     * @return MoipHttpResponse
     */
    public function getHttpResponse()
    {
        return $this->httpResponse;
    }

    /**
     * Get MoipHttpClient
     *
     * @return MoipHttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Get results
     *
     * @return Illuminate\Support\Collection
     */
    public function getResults()
    {
        return $this->httpResponse->getResults();
    }

    /**
     * Populate resource
     *
     * @return $this
     */
    public function populate(MoipHttpResponse $response)
    {
        if (is_array($response)) {
            $this->data = (object) $response;
            return $this;
        }

        $this->httpResponse = $response;
        $this->data = $response->getResults();

        return $this;
    }

    /**
     * Generate Moip Code
     */
    public function generateCode()
    {
        return uniqid();
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

}
