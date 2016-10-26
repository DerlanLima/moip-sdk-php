<?php

namespace Softpampa\Moip\Exceptions;

use RuntimeException;

class ResourceNotFoundException extends RuntimeException {

    /**
     * Constructor.
     */
    public function __construct($requestException)
    {
        $url = $requestException->getRequest()->getUrl();

        parent::__construct("Resource {$url} not found", 404);
    }

}
