<?php

namespace Softpampa\Moip\Contracts;

interface Client {

    /**
     * Constructor.
     *
     * @param  Authenticatable  $auth
     * @param  string  $enviroment
     */
    public function __construct(Authenticatable $auth, $enviroment);

    /**
     * Add Request Query String
     *
     * @param  string  $query
     * @return $this
     */
    public function addQueryString($query);

    /**
     * Set Request URI
     *
     * @param  string  $uri
     * @return $this
     */
    public function setUri($uri);

    /**
     * Set Request URL API Version
     *
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * Set Request URL Path
     *
     * @param  string  $path
     * @return $this
     */
    public function setPath($path);

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
    public function getHttpMethod();

    /**
     * Get Moip Response Object
     *
     * @return MoipResponse
     */
    public function getResponse();

    /**
     * Send HTTP Request method GET
     *
     * @return MoipResponse
     */
    public function get($route = '', $binds = []);

    /**
     * Send HTTP Request method PUT
     *
     * @return MoipResponse
     */
    public function put($route, $binds = [], $payload = []);

    /**
     * Send HTTP Request method POST
     *
     * @return MoipResponse
     */
    public function post($route, $binds = [], $payload = []);

//    public function delete();
}
