<?php

namespace Softpampa\Moip;

use stdClass;
use Illuminate\Support\Collection;
use GuzzleHttp\Message\Response as HttpResponse;
use Softpampa\Moip\Contracts\Response;
use Softpampa\Moip\Exceptions\Server\ServerRequestException;
use Softpampa\Moip\Exceptions\Client\ValidationException;
use Softpampa\Moip\Exceptions\Client\UnauthorizedException;
use Softpampa\Moip\Exceptions\Client\ClientRequestException;
use Softpampa\Moip\Exceptions\Client\ResourceNotFoundException;

class MoipResponse implements Response {

    /**
     * Guzzle Http Response
     *
     * @var  \GuzzleHttp\Message\Response
     */
    protected $response;

    /**
     * @var \stdClass
     */
    protected $content;

    /**
     * JSON response data key
     *
     * @var string
     */
    protected $dataKey;

    /**
     * List of errors
     *
     * @var array
     */
    protected $errors;

    /**
     * Constructor.
     *
     * @param  \GuzzleHttp\Message\Response $response
     * @param  string  $dataKey
     */
    public function __construct(HttpResponse $response, $dataKey)
    {
        $this->response = $response;
        $this->dataKey = $dataKey;
        $this->content = json_decode($this->getBodyContent());

        if ($this->hasErrors()) {
            $this->analyzeResponseErrors();
        }
    }

    /**
     * Get response HTTP Code Status
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get response HTTP Body Content
     *
     * @return string
     */
    public function getBodyContent()
    {
        return (string) $this->response->getBody();
    }

    /**
     * Check if has HTTP errors in response
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->getStatusCode() >= 400;
    }

    /**
     * Change resource
     *
     * @param  string  $dataKey
     * @return $this
     */
    public function setDataKey($dataKey)
    {
        $this->dataKey = $dataKey;

        return $this;
    }

    /**
     * Analyze errors from response
     *
     * @return void
     */
    protected function analyzeResponseErrors()
    {
        $content = $this->content;

        if ($content && property_exists($content, 'errors')) {
            $this->setErrors($content, 'errors');
        } else if ($content && property_exists($content, 'ERROR')) {
            $this->setError('Unknown', $content->ERROR);
        }

        $this->throwExceptions();
    }

    /**
     * Set errors from response
     *
     * @param  array  $response
     * @param  string  $key
     * @return void
     */
    protected function setErrors($response, $key)
    {
        foreach ($response->{$key} as $error) {
            $this->setError($error->code, $error->description);
        }
    }

    /**
     * Set a error
     *
     * @param  string  $code
     * @param  string  $description
     * @return void
     */
    public function setError($code, $description)
    {
        $error = new stdClass;
        $error->code = $code;
        $error->description = $description;

        $this->errors[] = $error;
    }

    /**
     * Throw request exceptions
     *
     * @throws ValidationException
     * @throws UnauthorizedException
     * @throws ResourceNotFoundException
     * @throws ClientRequestException
     * @throws ServerRequestException
     * @return void
     */
    protected function throwExceptions()
    {
        $status = $this->getStatusCode();

        switch ($status) {
            case 400:
                throw new ValidationException($this);
                break;
            case 401:
                throw new UnauthorizedException($this);
                break;
            case 404:
                throw new ResourceNotFoundException($this);
                break;
            default:
                //
                break;
        }

        if ($status >= 400 && $status < 500) {
            throw new ClientRequestException($this, 'Whoops looks like something went wrong');
        } elseif ($status >= 500) {
            throw new ServerRequestException($this);
        }
    }

    /**
     * Get effective URL
     *
     * @return string
     */
    public function getEffectiveUrl()
    {
        return $this->response->getEffectiveUrl();
    }

    /**
     * Return all errors
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function getErrors()
    {
        if ($this->errors) {
            return new Collection($this->errors);
        }

        return $this->errors;
    }

    /**
     * Return response content
     *
     * @return \Illuminate\Support\Collection
     */
    public function getResults()
    {
        $key = $this->dataKey;
        $content = $this->content;

        if (is_object($content) && property_exists($content, $key)) {
            return new Collection($content->{$key});
        } else if (is_array($content)) {
            return new Collection($content);
        }

        return $content;
    }

}
