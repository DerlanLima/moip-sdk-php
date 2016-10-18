<?php

namespace Softpampa\Moip;

use GuzzleHttp\Message\Response;
use Illuminate\Support\Collection;

class MoipHttpResponse {

    /**
     * @var  GuzzleHttp\Message\Response  $response
     */
    protected $response;

    /**
     * @var  stdClass  $responseContent
     */
    protected $responseContent;

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
     * @param  GuzzleHttp\Message\Response $response
     * @param  string  $resource
     */
    public function __construct(Response $response, $resource)
    {
        $this->response = $response;
        $this->resource = $resource;
        $this->responseContent = json_decode($response->getBody()->getContents());

        if ($this->hasErrors()) {
            $this->setResponseErrors();
        }
    }

    /**
     * Get Client Http Response
     *
     * @return GuzzleHttp\Message\Response
     */
    public function getHttpResponse()
    {
        return $this->response;
    }

    /**
     * Check if has Http Error
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->response->getStatusCode() >= 400;
    }

    /**
     * Check if has Http Client Error
     *
     * @return boolean
     */
    public function hasClientErrors()
    {
        return $this->response->getStatusCode() >= 400 && $this->response->getStatusCode() < 500;
    }

    /**
     * Check if has Http Server Error
     *
     * @return boolean
     */
    public function hasServerErrors()
    {
        return $this->response->getStatusCode() >= 500;
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
        if (isset($this->responseContent) && property_exists($this->responseContent, 'errors')) {
            foreach ($this->responseContent->errors as $error) {
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
    protected function setError($code, $description)
    {
        $this->errors[] = (object) [
                    'code' => $code,
                    'description' => $description
        ];
    }

    /**
     * Return all errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Return response content
     *
     * @return Illuminate\Support\Collection
     */
    public function getResults()
    {
        if (isset($this->responseContent) && property_exists($this->responseContent, $this->resource)) {
            return new Collection($this->responseContent->{$this->resource});
        }

        return $this->responseContent;
    }

}
