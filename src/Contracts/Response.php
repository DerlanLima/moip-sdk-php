<?php

namespace Softpampa\Moip\Contracts;

interface Response {

    /**
     * Set Response resource
     *
     * @param  strinf  $resource
     */
    public function setResource($resource);

    /**
     * Get Response HTTP Body content
     *
     * @return string
     */
    public function getBodyContent();

    /**
     * Get Response HTTP Code Status
     *
     * @return int
     */
    public function getStatusCode();

    /**
     * Check if response has errors
     *
     * @return true
     */
    public function hasErrors();

    /**
     * Return all errors
     *
     * @return stdClass
     */
    public function getErrors();

    /**
     * Set an error
     *
     * @param  string  $code
     * @param  string  $description
     * @return stdClass
     */
    public function setError($code, $description);

    /**
     * Get response Resource Object
     *
     * @return MoipResource
     */
    public function getResults();
}
