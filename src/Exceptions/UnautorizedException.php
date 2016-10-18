<?php

namespace Softpampa\Moip\Exceptions;

use RuntimeException;

class UnautorizedException extends RuntimeException {

    /**
     * UnautorizedException constructor.
     */
    public function __construct()
    {
        // error string is in portuguese because the error descriptions returned by the API are also in portuguese
        parent::__construct('[401] Erro de Autenticação. confirme se sua autenticação pode realizar a ação desejada e se está usando o ambiente correto, lembre-se que as chaves de produção e de sandbox são distintas.', 401);
    }

}
