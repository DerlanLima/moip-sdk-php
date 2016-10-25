<?php

namespace Softpampa\Moip\Exceptions;

use RuntimeException;
use Softpampa\Moip\MoipResponse;
use GuzzleHttp\Exception\RequestException;

class ValidationException extends RuntimeException {

    /**
     * @var  Softpampa\Moip\MoipResponse  $response
     */
    private $response;

    /**
     * @var  GuzzleHttp\Message\Request  $request
     */
    private $request;

    /**
     * Constructor.
     *
     * @param  GuzzleHttp\Exception\RequestException  $requestException
     */
    public function __construct(RequestException $requestException)
    {
        $this->response = new MoipResponse($requestException->getResponse(), '');
        $this->request = $requestException->getRequest();

        parent::__construct('Erro na requisição! Os dados enviados não passaram na validação do Moip.');
    }

    /**
     * Returns HTTP Status Code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Returns the list of errors returned by the API.
     *
     * @return Illuminate\Support\Collection
     */
    public function getErrors()
    {
        return $this->response->getErrors();
    }

    /**
     * Get HTTP request method
     *
     * @return string
     */
    public function getHttpRequestMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * Get HTTP request URL
     *
     * @return string
     */
    public function getHttpRequestUrl()
    {
        return $this->request->getUrl();
    }

    /**
     * Convert errors variables in string.
     *
     * @return string
     */
    public function __toString()
    {
        $code = $this->getStatusCode();
        $template = "[$code] The following errors ocurred:\n%s";
        $errorsList = '';

        foreach ($this->getErrors() as $error) {
            $code = $error->code;
            $desc = $error->description;
            $errorsList .= "$code: $desc\n";
        }

        return sprintf($template, $errorsList);
    }

}
