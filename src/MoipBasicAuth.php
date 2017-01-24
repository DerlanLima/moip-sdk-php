<?php

namespace Softpampa\Moip;

use Softpampa\Moip\Contracts\Authenticatable;

class MoipBasicAuth implements Authenticatable
{

    /**
     * Moip token
     *
     * @var string
     */
    protected $token;

    /**
     * Moip Key
     *
     * @var string
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param  string  $token
     * @param  string  $key
     */
    public function __construct($token, $key)
    {
        $this->token = $token;
        $this->key = $key;
    }

    /**
     * Generate authentication key.
     *
     * @return string
     */
    public function generateAuthorization()
    {
        return 'Basic ' . base64_encode($this->token . ':' . $this->key);
    }
}
