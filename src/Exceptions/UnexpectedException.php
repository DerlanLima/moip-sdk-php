<?php

namespace Moip\Exceptions;

use RuntimeException;

class UnexpectedException extends RuntimeException {

    /**
     * UnexpectedException constructor.
     */
    public function __construct($previous = null)
    {
        parent::__construct('Um erro inesperado aconteceu, por favor contate o Moip', 500, $previous);
    }

}
