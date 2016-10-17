<?php

namespace Softpampa\Moip;

class MoipAuth {

    /**
     * @var  string  $token  Moip Token
     */
    protected $token;

    /**
     * @var  string  $key  Moip Key
     */
    protected $key;

    /**
     * Constructor.
     *
     * @param  string  $token  Moip Token
     * @param  string  $key  Moip Key
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
    public function generateAuthorizationKey()
    {
        return 'Basic ' . base64_encode($this->token . ':' . $this->key);
    }

}
