<?php

namespace Softpampa\Moip;

use stdClass;
use JsonSerializable;

abstract class MoipResource implements JsonSerializable {

    /**
     * @var  MoipHttpClient  $httpClient  Moip HTTP Client
     */
    protected $client;

    /**
     * @var  stdClass  $data  Resource
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param  MoipHttpClient  $client  HTTP Client
     */
    public function __construct(MoipHttpClient $client)
    {
        $this->data = new stdClass;
        $this->client = $client;

        $this->initialize();
    }

    /**
     * Initialize a resource
     *
     * @return void
     */
    protected function initialize()
    {
        $this->prepareResourcePath();
    }

    /**
     * Prepare client for request
     *
     * @return void
     */
    protected function prepareResourcePath()
    {
        $this->client->setPath($this->path);
    }

    /**
     * Get Resource Path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get MoipHttpResponse
     *
     * @return MoipHttpResponse
     */
    public function getResponse()
    {
        return $this->client->getResponse();
    }

    /**
     * Get MoipHttpClient
     *
     * @return MoipHttpClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get results
     *
     * @return Illuminate\Support\Collection
     */
    public function getResults()
    {
        return $this->getResponse()->getResults();
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
     * Define limit
     *
     * @param  int  $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->client->addQueryString([
            'limit' => (int) $limit
        ]);

        return $this;
    }

    /**
     * Define offset
     *
     * @param  int  $offset
     * @return $this
     */
    public function offset($offset)
    {
        $this->client->addQueryString([
            'offset' => (int) $offset
        ]);

        return $this;
    }

    /**
     * Define filter
     *
     * @param  int  $offset
     * @return $this
     */
    public function addFilter($type, $key, $value)
    {
        $this->client->addQueryString([
            'filters' => "{$key}::{$type}({$value})|"
        ]);

        return $this;
    }

    /**
     * Define filter `in`
     *
     * @param  string  $key
     * @param  array  $values
     * @return $this
     */
    public function in($key, $values)
    {
        $values = implode(',', $values);

        $this->client->addQueryString([
            'filters' => "{$key}::in({$value})|"
        ]);

        return $this;
    }

    /**
     * Define filter `between`
     *
     * @param  string  $key
     * @param  string  $a
     * @param  string  $b
     * @return $this
     */
    public function between($key, $a, $b)
    {

        $this->client->addQueryString([
            'filters' => "{$key}::bt({$a},{$b})|"
        ]);

        return $this;
    }

    /**
     * Get resource property
     *
     * @param  string  $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this->data, $name)) {
            return $this->data->$name;
        }

        return null;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return stdClass
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

}
