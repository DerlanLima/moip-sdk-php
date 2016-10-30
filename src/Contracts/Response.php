<?php

namespace Softpampa\Moip\Contracts;

interface Response {

    /**
     * Set response resource data key
     *
     * @param  string  $key
     */
    public function setDataKey($key);

    /**
     * Get response HTTP Body content
     *
     * @return string
     */
    public function getBodyContent();

    /**
     * Get response HTTP Code Status
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
     * @return \Illuminate\Support\Collection;
     */
    public function getErrors();

    /**
     * Get response Resource Object
     *
     * @return \Illuminate\Support\Collection;
     */
    public function getResults();
}
