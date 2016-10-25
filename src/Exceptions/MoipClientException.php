<?php

namespace Softpampa\Moip\Exceptions;

use RuntimeException;
use GuzzleHttp\Exception\RequestException;
use Softpampa\Moip\Exceptions\ValidationException;
use Softpampa\Moip\Exceptions\UnautorizedException;

class MoipClientException extends RuntimeException {

    /**
     * MoipClientException constructor.
     */
    public function __construct(RequestException $e)
    {
        if (! $response = $e->getResponse()) {
            throw new UnexpectedException($e);
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode == 401) {
            throw new UnautorizedException;
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            throw new ValidationException($e);
        }

    }

}
