<?php

namespace Softpampa\Moip;

use UnexpectedValueException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\StreamInterface;
use GuzzleHttp\Exception\RequestException;
use Softpampa\Moip\Traits\Utils;
use Softpampa\Moip\Contracts\Client;
use Softpampa\Moip\Contracts\Authenticatable;
use Softpampa\Moip\Exceptions\MoipClientException;

class MoipClient implements Client {

    use Utils;

    /**
     * @var  Authenticatable  $auth  Moip Authentication
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
    protected $httpClient;

    /**
     * @var  Request  $request  HTTP Client Request
     */
    protected $request;

    /**
     * @var  MoipResponse  $response  Moip Response
     */
    protected $response;

    /**
     * @var  array  $mocks  Response Mocks
     */
    protected $mocks;

    /**
     * @var  array  $options Moip API Options
     */
    protected $options = [
        'exceptions' => false,
        'timeout' => 10,
        'connect_timeout' => 10
    ];

    /**
     * Constructor.
     *
     * @param  Authenticatable  $auth  Moip Authentication method
     * @param  string  $environment  Moip environment
     * @param  array  $options  Moip options
     */
    public function __construct(Authenticatable $auth, $environment, $options = [])
    {
        $this->auth = $auth;
        $this->httpClient = new HttpClient;
        $this->environment = $environment;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Setup HTTP Client settings.
     *
     * @return void
     */
    protected function setupHttpClient()
    {
        $this->httpClient->setDefaultOption('exceptions', $this->options['exceptions']);
        $this->httpClient->setDefaultOption('timeout', 10);
        $this->httpClient->setDefaultOption('connect_timeout', 10);

        $this->httpClient->setDefaultOption('headers', [
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
        $this->setupHttpClient();
        $this->setMockResponses();

        $this->request = $this->httpClient->createRequest($method, $this->environment, ['json' => $payload]);

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
            $response = $this->httpClient->send($this->request);
        } catch (RequestException $e) {
            throw new MoipClientException($e);
        }

        return $this->response = new MoipResponse($response, $this->path);
    }

    /**
     * Set Mocks Responses
     *
     * @return void
     */
    protected function setMockResponses()
    {
        if (! empty($this->mocks)) {
            $this->httpClient->getEmitter()->attach(new Mock($this->mocks));
        }
    }

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
     * HTTP method DELETE
     *
     * @param  string  $route
     * @param  array  $binds
     * @return MoipResponse
     */
    public function delete($route, $binds = [])
    {
        return $this->makeHttpRequest('DELETE', $this->interpolate($route, $binds));
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

    /**
     * Add a mock response
     *
     * @param  int  $codeStatus  HTTP Code Status
     * @param  StreamInterface  $body  HTTP Body
     * @param  array  $headers  HTTP Headers
     * @return $this
     */
    public function addMockResponse($codeStatus, $body = null, $headers = [])
    {
        $headers = array_merge($headers, ['Content-Type' => 'application/json']);
        $this->mocks[] = new Response($codeStatus, ['Content-Type' => 'application/json'], $body);

        return $this;
    }

    /**
     * Set default option
     *
     * @param  string  $option
     * @param  string  $value
     * @return void
     */
    public function setDefaultOption($option, $value)
    {
        if (! array_key_exists($option, $this->options)) {
            throw new UnexpectedValueException("Unexpected MoipClient {$option} option");
        }

        $this->options[$option] = $value;
    }

    /**
     * Get HTTP Client
     *
     * @return Request
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Get request body content
     *
     * @return string
     */
    public function getBodyContent()
    {
        return (string) $this->request->getBody();
    }

    /**
     * Get HTTP request method
     *
     * @return int
     */
    public function getHttpMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * Get HTTP request URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->request->getUrl();
    }

}
