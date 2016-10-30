<?php

namespace Softpampa\Moip\Exceptions\Client;

use Exception;
use Softpampa\Moip\MoipResponse;
use Softpampa\Moip\Exceptions\RequestException;

class ClientRequestException extends RequestException  {

     /**
     * Constructor
     *
     * @param  \Softpampa\Moip\MoipResponse  $response
     * @param  string  $message
     * @param  \Exception  $previous
     */
    public function __construct(MoipResponse $response, $message, Exception $previous = null)
    {
        parent::__construct($response, $message, $previous);
    }

    /**
     * Convert errors variables in string.
     *
     * @return string
     */
    public function __toString()
    {
        $code = $this->response->getStatusCode();

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
