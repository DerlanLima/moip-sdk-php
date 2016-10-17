<?php

namespace Softpampa\Moip;

use GuzzleHttp\Client;
use Softpampa\Moip\Traits\Utils;
use Softpampa\Moip\MoipHttpResponse;

class MoipHttpClient {

    use Utils;

    /**
     * @var  string  $auth  Moip Authentication
     */
    protected $auth;

    /**
     * @var  string  $environment  Moip Environment
     */
    protected $environment;

    /**
     * @var  string  $path  Moip Environment
     */
    protected $path;

    /**
     * @var  string  $queryString  Request Query String
     */
    protected $queryString = [];

    /**
     * @var  string  $version  Moip Environment
     */
    protected $version;

    /**
     * @var  GuzzleHttp\Client  $httpClient  HTTP Client
     */
    protected $httpClient;

    /**
     * @var  GuzzleHttp\Message\Request  $httpClient  HTTP Client Request
     */
    protected $request;

    /**
     * @var  GuzzleHttp\Message\Response  $httpClient  HTTP Client Response
     */
    protected $response;

    /**
     * Constructor.
     *
     * @param  string  $token  Moip Token
     * @param  string  $key  Moip Key
     * @param  string  $environment  Moip Environment
     */
    public function __construct($auth, $environment)
    {
        $this->auth = $auth;
        $this->environment = $environment;
        $this->httpClient = new Client;

        $this->setupHttpClient();
    }

    /**
     * Setup HTTP Client settings.
     *
     * @return void
     */
    protected function setupHttpClient()
    {
        $this->httpClient->setDefaultOption('exceptions', false);
        $this->httpClient->setDefaultOption('timeout', 10);
        $this->httpClient->setDefaultOption('connect_timeout', 10);

        $this->httpClient->setDefaultOption('headers', [
            'Authorization' => $this->auth->generateAuthorizationKey()
        ]);
    }

    /**
     * Set URL Request Paths
     *
     * @param  string  $params
     * @return void
     */
    protected function setRequestUrlPaths($params)
    {
        if (isset($this->uri)) {
            $fullPath = $this->uri . '/';
        }

        $fullPath .= sprintf('%s/%s', $this->version, $this->path);

        if (isset($params)) {
            $fullPath .= $params;
        }

        $this->request->setPath($fullPath);
    }

    /**
     * Set URL Request Query String
     *
     * @return void
     */
    protected function setRequestQueryString()
    {
        $query = $this->request->getQuery();

        foreach ($this->queryString as $key => $value) {
            $query->set($key, $value);
        }
    }

    /**
     * Add Query String to Request
     *
     * @param  array  $query
     * @return $this
     */
    public function addQueryString(array $query)
    {
        $this->queryString = array_merge($this->queryString, $query);

        return $this;
    }

    /**
     * Create a request to Moip API
     *
     * @param  string  $method
     * @param  string  $params
     * @param  array  $payload
     * @return MoipHttpResponse
     */
    protected function makeHttpRequest($method, $params = null, $payload = [])
    {
        $this->request = $this->httpClient->createRequest($method, $this->environment, ['json' => $payload]);

        $this->setRequestQueryString();
        $this->setRequestUrlPaths($params);

        var_dump($payload);

        return $this->sendHttpRequest();
    }

    /**
     * Send a request to Moip API
     *
     * @return MoipHttpResponse
     */
    protected function sendHttpRequest()
    {
        $this->response = $this->httpClient->send($this->request);

        return new MoipHttpResponse($this->response, $this->path);
    }

    public function getHttpRequest()
    {
        return $this->request;
    }

    /**
     * HTTP method GET
     *
     * @param  string  $route
     * @param  array  $binds
     * @return MoipHttpResponse
     */
    public function get($route = '', $binds = [])
    {
        return $this->makeHttpRequest('GET', $this->interpolate($route, $binds));
    }

    /**
     * HTTP method PUT
     *
     * @param  string  $route
     * @param  array  $binds
     * @param  array  $payload
     * @return MoipHttpResponse
     */
    public function put($route, $binds = [], $payload = [])
    {
        return $this->makeHttpRequest('PUT', $this->interpolate($route, $binds), $payload);
    }

    /**
     * HTTP method POST
     *
     * @param  string  $route
     * @param  array  $binds
     * @param  array  $payload
     * @return MoipHttpResponse
     */
    public function post($route, $binds = [], $payload = [])
    {
        return $this->makeHttpRequest('POST', $this->interpolate($route, $binds), $payload);
    }

    /**
     * Set Request URI.
     *
     * @param  string  $uri
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Set Request Path.
     *
     * @param  string  $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set Request Version.
     *
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

}
