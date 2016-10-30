<?php

namespace Softpampa\Moip\Exceptions\Client;

use Softpampa\Moip\MoipResponse;

class UnauthorizedException extends ClientRequestException {

    /**
     * Constructor.
     *
     * @param  \Softpampa\Moip\MoipResponse  $response
     */
    public function __construct(MoipResponse $response)
    {
        parent::__construct($response, 'Erro de Autenticação! Confirme se sua autenticação pode realizar a ação desejada e se está usando o ambiente correto, lembre-se que as chaves de produção e de sandbox são distintas.');
    }

}
