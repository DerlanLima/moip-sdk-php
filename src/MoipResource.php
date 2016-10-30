<?php

namespace Softpampa\Moip;

use stdClass;
use JsonSerializable;
use Softpampa\Moip\Contracts\Resource;

abstract class MoipResource implements JsonSerializable, Resource {

    /**
     * Moip client
     *
     * @var \Softpampa\Moip\MoipClient
     */
    protected $client;

    /**
     * Moip API
     *
     * @var \Softpampa\Moip\MoipApi
     */
    protected $api;

    /**
     * Resource data
     *
     * @var \stdClass
     */
    protected $data;

    /**
     * Moip event system
     *
     * @var \Softpampa\Moip\MoipEvent
     */
    protected $event;

    /**
     * Constructor.
     *
     * @param  \Softpampa\Moip\MoipApi  $api
     */
    public function __construct(MoipApi $api)
    {
        $this->api = $api;
        $this->event = $api->getMoip()->getEvent();
        $this->client = $api->getClient();

        $this->initialize();
    }

    /**
     * Initialize a resource
     *
     * @return void
     */
    protected function initialize()
    {
        $this->data = new stdClass;
        $this->prepareResourcePath();
    }

    /**
     * Prepare client for request
     *
     * @return void
     */
    protected function prepareResourcePath()
    {
        $this->client->setResource($this->resource);
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
     * Moip client
     *
     * @return \Softpampa\Moip\MoipClient
     */
    public function getClient()
    {
        return $this->client;
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
     * @param  string  $pattern
     * @param  array  $binds
     * @return $this
     */
    public function addFilter($pattern, array $binds)
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
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

}
