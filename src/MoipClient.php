<?php

namespace Softpampa\Moip;

use UnexpectedValueException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock;
use Softpampa\Moip\Contracts\Client;
use Softpampa\Moip\Contracts\Authenticatable;

class MoipClient implements Client {

    /**
     * Moip API authentication
     *
     * @var \Softpampa\Moip\Contracts\Authenticatable
     */
    protected $auth;

    /**
     * Moip API environment
     *
     * @var string
     */
    protected $environment;

    /**
     * Request URL path
     *
     * @var string
     */
    protected $path;

    /**
     * Moip API version
     *
     * @var string
     */
    protected $version;

    /**
     * Moip API resource
     *
     * @var string
     */
    protected $resource;

    /**
     * Moip API param
     *
     * @var string
     */
    protected $param;

    /**
     * Request URL query strings
     * @var array
     */
    protected $queryStrings = [];

    /**
     * Guzzle Http Client
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Guzzle Http Request
     *
     * @var \GuzzleHttp\Message\Request
     */
    protected $request;

    /**
     * Moip Response
     *
     * @var \Softpampa\Moip\MoipResponse
     */
    protected $response;

    /**
     * Mocks responses
     *
     * @var array
     */
    protected $mocks;

    /**
     * Moip client default options
     *
     * @var array
     */
    protected $options = [
        'timeout' => 10,
        'connect_timeout' => 10
    ];

    /**
     * Constructor
     *
     * @param  \Softpampa\Moip\Contracts\Authenticatable $auth
     * @param  string  $environment
     * @param  array  $options
     */
    public function __construct(Authenticatable $auth, $environment, $options = [])
    {
        $this->auth = $auth;
        $this->environment = $environment;
        $this->httpClient = new HttpClient;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Setup HTTP Client settings.
     *
     * @return void
     */
    protected function setupHttpClientSettings()
    {
        $this->httpClient->setDefaultOption('exceptions', false);
        $this->httpClient->setDefaultOption('timeout', $this->options['timeout']);
        $this->httpClient->setDefaultOption('connect_timeout', $this->options['connect_timeout']);

        $this->httpClient->setDefaultOption('headers', [
            'Content-Type' => 'application/json',
            'Authorization' => $this->auth->generateAuthorization()
        ]);
    }

    /**
     * Set URL Request Paths
     *
     * @return void
     */
    protected function setRequestFullPath()
    {
        $fullPath = sprintf('%s/%s/%s/%s', $this->path, $this->version, $this->resource, $this->param);

        $this->request->setPath(str_replace('//', '', rtrim($fullPath, '/')));
    }

    /**
     * Set URL Request Query String
     *
     * @return void
     */
    protected function setRequestQueryString()
    {
        $query = $this->request->getQuery();

        foreach ($this->queryStrings as $key => $value) {
            $query->set($key, $value);
        }
    }

    /**
     * Create a request to Moip API
     *
     * @param  string  $method
     * @param  array  $payload
     * @return \Softpampa\Moip\MoipResponse
     */
    protected function createRequest($method, $payload = [])
    {
        $this->setMockResponses();
        $this->setupHttpClientSettings();

        $this->request = $this->httpClient->createRequest($method, $this->environment, ['json' => $payload]);

        $this->setRequestFullPath();
        $this->setRequestQueryString();

        return $this->send();
    }

    /**
     * Send a request to Moip API
     *
     * @return \Softpampa\Moip\MoipResponse
     */
    protected function send()
    {
        $response = $this->httpClient->send($this->request);

        return $this->response = new MoipResponse($response, $this->resource);
    }

    /**
     * Set Mocks Responses
     *
     * @return void
     */
    protected function setMockResponses()
    {
        if ($this->mocks) {
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
     * @return \Softpampa\Moip\MoipResponse
     */
    public function get($route = '', array $binds = [])
    {
        $this->setParam($route, $binds);

        return $this->createRequest('GET');
    }

    /**
     * HTTP method PUT
     *
     * @param  string  $route
     * @param  array  $binds
     * @param  array  $payload
     * @return \Softpampa\Moip\MoipResponse
     */
    public function put($route, array $binds = [], $payload = [])
    {
        $this->setParam($route, $binds);

        return $this->createRequest('PUT', $payload);
    }

    /**
     * HTTP method POST
     *
     * @param  string  $route
     * @param  array  $binds
     * @param  array  $payload
     * @return \Softpampa\Moip\MoipResponse
     */
    public function post($route, array $binds = [], $payload = [])
    {
        $this->setParam($route, $binds);

        return $this->createRequest('POST', $payload);
    }

    /**
     * HTTP method DELETE
     *
     * @param  string  $route
     * @param  array  $binds
     * @return \Softpampa\Moip\MoipResponse
     */
    public function delete($route, array $binds = [])
    {
        $this->setParam($route, $binds);

        return $this->createRequest('DELETE');
    }

    /**
     * Set request path
     *
     * @param  string  $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = trim($path, '/');

        return $this;
    }

    /**
     * Set request API version
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
     * Add request path
     *
     * @param  string  $resource
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = trim($resource, '/');

        return $this;
    }

    /**
     * Set request param
     *
     * @param  string  $param
     * @param  array  $binds
     * @return $this
     */
    public function setParam($param, array $binds = [])
    {
        $this->param = preg_replace_callback('/\{\w+\}/', function() use (&$binds) {
            return array_shift($binds);
        }, trim($param, '/'));

        return $this;
    }

    /**
     * Add Query String to Request
     *
     * @param  string  $key
     * @param  string  $value
     * @return $this
     */
    public function addQueryString($key, $value)
    {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        $item[$key] = (string) $value;

        $this->queryStrings[] = $item;

        return $this;
    }

    /**
     * Add a mock response
     *
     * @param  string  $content
     * @return $this
     */
    public function addMockResponse($content)
    {
        $this->mocks[] = $content;

        return $this;
    }

    /**
     * Set default option
     *
     * @param  string  $option
     * @param  string  $value
     * @throws UnexpectedValueException
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
     * Get Guzzle Http Client
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Get request body content
     *
     * @return string|null
     */
    public function getBodyContent()
    {
        return $this->request ? (string) $this->request->getBody() : null;
    }

    /**
     * Get HTTP request method
     *
     * @return int|null
     */
    public function getMethod()
    {
        return $this->request ? $this->request->getMethod() : null;
    }

    /**
     * Get HTTP request URL
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->request ? $this->request->getUrl() : null;
    }

}
