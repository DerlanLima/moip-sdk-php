<?php

namespace Softpampa\Moip\Exceptions\Client;

use Softpampa\Moip\MoipResponse;

class ValidationException extends ClientRequestException {

    /**
     * Constructor.
     *
     * @param  \Softpampa\Moip\MoipResponse  $response
     */
    public function __construct(MoipResponse $response)
    {
        parent::__construct($response, 'The server cannot process the request due to an apparent client error');
    }

}
