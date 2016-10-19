<?php

namespace Softpampa\Moip\Contracts;

interface MoipResource {

    public function addFilter($pattern, $binds);
}
