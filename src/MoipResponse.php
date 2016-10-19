<?php

namespace Softpampa\Moip;

use stdClass;
use GuzzleHttp\Message\Response;
use Illuminate\Support\Collection;

class MoipResponse implements Contracts\MoipResponse {

    /**
     * @var  Response  $response
     */
    protected $response;

    /**
     * @var  stdClass  $content
     */
    protected $content;

    /**
     * @var  string  $resource
     */
    protected $resource;

    /**
     * @var  array  $errors
     */
    protected $errors;

    /**
     * Constructor.
     *
     * @param  Response $response
     * @param  string  $resource
     */
    public function __construct(Response $response, $resource)
    {
        $this->response = $response;
        $this->resource = $resource;
        $this->content = json_decode($this->getBodyContent());

        if ($this->hasErrors()) {
            $this->setResponseErrors();
        }
    }

    /**
     * Get response HTTP Code Status
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get response HTTP Body Content
     *
     * @return string
     */
    public function getBodyContent()
    {
        return (string) $this->response->getBody();
    }

    /**
     * Check if has HTTP errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->getStatusCode() >= 400;
    }

    /**
     * Check if has HTTP Client Error
     *
     * @return boolean
     */
    public function hasClientErrors()
    {
        return $this->getStatusCode() >= 400 && $this->getStatusCode() < 500;
    }

    /**
     * Check if has HTTP Server Error
     *
     * @return boolean
     */
    public function hasServerErrors()
    {
        return $this->getStatusCode() >= 500;
    }

    /**
     * Change resource
     *
     * @param  string  $resource
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Set errors from response
     *
     * @return void
     */
    protected function setResponseErrors()
    {
        if (isset($this->content) && property_exists($this->content, 'errors')) {

            foreach ($this->content->errors as $error) {
                $this->setError($error->code, $error->description);
            }
        }
    }

    /**
     * Set a error
     *
     * @param  string  $code
     * @param  string  $description
     * @return void
     */
    public function setError($code, $description)
    {
        $error = new stdClass;
        $error->code = $code;
        $error->description = $description;

        $this->errors[] = $error;
    }

    /**
     * Return all errors
     *
     * @return array
     */
    public function getErrors()
    {
        return new Collection($this->errors);
    }

    /**
     * Return response content
     *
     * @return Collection
     */
    public function getResults()
    {
        if (isset($this->content) && property_exists($this->content, $this->resource)) {
            return new Collection($this->content->{$this->resource});
        }

        return $this->content;
    }

}
