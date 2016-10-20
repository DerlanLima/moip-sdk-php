<?php

namespace Softpampa\Moip;

use stdClass;
use JsonSerializable;

abstract class MoipResource implements JsonSerializable, Contracts\MoipResource {

    /**
     * @var  MoipClient  $client  Moip HTTP Client
     */
    protected $client;

    /**
     * @var  Api  $api  Moip API
     */
    protected $api;

    /**
     * @var  stdClass  $data  Resource
     */
    protected $data;

    /**
     * @var  MoipEvent  $event
     */
    protected $event;

    /**
     * Constructor.
     *
     * @param  Api  $api  Moip Api
     */
    public function __construct(Api $api)
    {
        $this->data = new stdClass;
        $this->api = $api;
        $this->event = $api->getMoip()->getEvent();
        $this->client = $api->getMoip()->getClient();

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
        $this->client->setPath($this->resource);
    }

    /**
     * Get Resource Path
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set Resource Path
     *
     * @return string
     */
    public function setResource($path)
    {
        $this->resource = $path;

        return $this;
    }

    /**
     * Populate resource
     *
     * @return $this
     */
    public function populate(MoipResponse $response)
    {
        if (is_array($response)) {
            $this->data = (object) $response;

            return $this;
        }

        $this->data = $response->getResults();

        return $this;
    }

    /**
     * Define filter
     *
     * @param  int  $pattern
     * @param  int  $binds
     * @return $this
     */
    public function addFilter($pattern, $binds)
    {
        //

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
