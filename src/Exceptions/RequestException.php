<?php

namespace Softpampa\Moip\Exceptions;

use Exception;
use Softpampa\Moip\MoipResponse;

abstract class RequestException extends Exception {

    /**
     * Moip response
     *
     * @var \Softpampa\Moip\MoipResponse
     */
    protected $response;

    /**
     * Constructor
     *
     * @param  \Softpampa\Moip\MoipResponse  $response
     * @param  string  $message
     * @param  \Exception  $previous
     */
    public function __construct(MoipResponse $response, $message, Exception $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $response->getStatusCode(), $previous);
    }

    /**
     * Response has content
     *
     * @return boolean
     */
    public function hasResponse()
    {
        return ! empty($this->response->getBodyContent());
    }

    /**
     * Response has API errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->response->hasErrors();
    }

    /**
     * Get errors
     *
     * @return \Illuminate\Support\Collection
     */
    public function getErrors()
    {
        return $this->response->getErrors();
    }

    /**
     * Get response content
     *
     * @return string
     */
    public function getResponseContent()
    {
        return $this->response->getBodyContent();
    }

}