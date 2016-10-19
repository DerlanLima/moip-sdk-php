<?php

namespace Softpampa\Moip;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Exception\RequestException;
use Softpampa\Moip\Traits\Utils;
use Softpampa\Moip\MoipHttpResponse;
use Softpampa\Moip\Contracts\MoipAuthentication;
use Softpampa\Moip\Exceptions\UnexpectedException;
use Softpampa\Moip\Exceptions\UnautorizedException;

class MoipClient implements Contracts\MoipClient {

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
     * @var  Client  $httpClient  HTTP Client
     */
    protected $client;

    /**
     * @var  Request  $request  HTTP Client Request
     */
    protected $request;

    /**
     * @var  MoipResponse  $response  Moip Response
     */
    protected $response;

    /**
     * @var  string  $options Moip API Options
     */
    protected $options = [
        'exceptions' => false
    ];

    /**
     * Constructor.
     *
     * @param  string  $token  Moip Token
     * @param  string  $key  Moip Key
     * @param  string  $environment  Moip Environment
     */
    public function __construct(MoipAuthentication $auth, $environment, $options = [])
    {
        $this->auth = $auth;
        $this->client = new Client;
        $this->environment = $environment;
        $this->options = array_merge($this->options, $options);

        $this->setupHttpClient();
    }

    /**
     * Setup HTTP Client settings.
     *
     * @return void
     */
    protected function setupHttpClient()
    {
        $this->client->setDefaultOption('exceptions', $this->options['exceptions']);
        $this->client->setDefaultOption('timeout', 10);
        $this->client->setDefaultOption('connect_timeout', 10);

        $this->client->setDefaultOption('headers', [
            'Authorization' => $this->auth->generateAuthorization()
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
    public function addQueryString($query)
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
        $this->request = $this->client->createRequest($method, $this->environment, ['json' => $payload]);

        $this->setRequestQueryString();
        $this->setRequestUrlPaths($params);

        return $this->send();
    }

    /**
     * Send a request to Moip API
     *
     * @return MoipHttpResponse
     */
    protected function send()
    {
        try {
            $response = $this->client->send($this->request);
        } catch (RequestException $e) {

            if (!$e->hasResponse()) {
                throw new UnexpectedException($e);
            }

            $statusCode = $e->getResponse()->getStatusCode();

            if ($statusCode == 401) {
                throw new UnautorizedException;
            } elseif ($statusCode > 400 && $statusCode < 500) {
                
            }

            throw new UnexpectedException($e);
        }

        return $this->response = new MoipResponse($response, $this->path);
    }

    /**
     * Get Client Request
     *
     * @return Request
     */
//    public function getRequest()
//    {
//        return $this->request;
//    }

    /**
     * Get Client
     *
     * @return Client
     */
//    public function getClient()
//    {
//        return $this->httpClient;
//    }

    /**
     * Get Moip Response
     *
     * @return MoipResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * HTTP method GET
     *
     * @param  string  $route
     * @param  array  $binds
     * @return MoipResponse
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
     * @return MoipResponse
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
     * @return MoipResponse
     */
    public function post($route, $binds = [], $payload = [])
    {
        return $this->makeHttpRequest('POST', $this->interpolate($route, $binds), $payload);
    }

    /**
     * Set Request URI
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
     * Set Request Path
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
     * Set Request Version
     *
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    public function getBodyContent()
    {
        return (string) $this->request->getBody();
    }

    public function getHttpMethod()
    {
        //
    }

    public function getUrl()
    {
        //
    }

}
