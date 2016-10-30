<?php

namespace Softpampa\Moip\Exceptions;

use Softpampa\Moip\MoipResponse;

class ServerRequestException extends RequestException {

    /**
     * Constructor.
     *
     * @param  \Softpampa\Moip\MoipResponse  $response
     */
    public function __construct(MoipResponse $response)
    {
        parent::__construct($response, 'Whoops looks like something went wrong, please contact Moip [https://moip.com.br]');
    }

}