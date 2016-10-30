<?php

namespace Softpampa\Moip\Contracts;

interface Client {

    /**
     * Constructor
     *
     * @param  \Softpampa\Moip\Contracts\Authenticatable  $auth
     * @param  string  $environment
     */
    public function __construct(Authenticatable $auth, $environment);

    /**
     * Add request query string
     *
     * @param  string  $key
     * @param  string  $value
     * @return $this
     */
    public function addQueryString($key, $value);

    /**
     * Set request URL path
     *
     * @param  string  $path
     * @return $this
     */
    public function setPath($path);

    /**
     * Set request URL API version
     *
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * Set request resource
     *
     * @param  string  $resource
     * @return $this
     */
    public function setResource($resource);

    /**
     * Set request param
     *
     * @param  string  $param
     * @param  array  $binds
     * @return $this
     */
    public function setParam($param, array $binds = []);

    /**
     * Get HTTP request URL
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get HTTP request body
     *
     * @return string
     */
    public function getBodyContent();

    /**
     * Get HTTP request method
     *
     * @return string
     */
    public function getMethod();

    /**
     * Get Moip Response Object
     *
     * @return \Softpampa\Moip\MoipResponse
     */
    public function getResponse();

    /**
     * Send HTTP request method GET
     *
     * @param  string  $route
     * @param  array  $binds
     * @return \Softpampa\Moip\MoipResponse
     */
    public function get($route = '', array $binds = []);

    /**
     * Send HTTP request method PUT
     *
     * @param  string  $route
     * @param  array  $binds
     * @param  array  $payload
     * @return \Softpampa\Moip\MoipResponse
     */
    public function put($route, array $binds = [], $payload = []);

    /**
     * Send HTTP request method POST
     *
     * @param  string  $route
     * @param  array  $binds
     * @param  array  $payload
     * @return \Softpampa\Moip\MoipResponse
     */
    public function post($route, array $binds = [], $payload = []);

    /**
     * Send HTTP request method DELETE
     *
     * @param  string  $route
     * @param  array  $binds
     * @return \Softpampa\Moip\MoipResponse
     */
    public function delete($route, array $binds = []);

}
