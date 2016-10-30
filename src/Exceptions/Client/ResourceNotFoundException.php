<?php

namespace Softpampa\Moip\Exceptions\Client;

use Softpampa\Moip\MoipResponse;

class ResourceNotFoundException extends ClientRequestException  {

    /**
     * Constructor.
     *
     * @param  \Softpampa\Moip\MoipResponse  $response
     */
    public function __construct(MoipResponse $response)
    {
        parent::__construct($response, "Resource {$response->getEffectiveUrl()} not found");
    }

}
