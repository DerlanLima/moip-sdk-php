<?php

namespace Softpampa\Moip;

abstract class MoipApi {

    /**
     * Moip API
     * @var \Softpampa\Moip\Moip
     */
    protected $moip;

    /**
     * Moip client
     *
     * @var \Softpampa\Moip\MoipClient
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param  \Softpampa\Moip\Moip  $moip
     */
    public function __construct(Moip $moip)
    {
        $this->moip = $moip;
        $this->client = new MoipClient($moip->getAuth(), $this->setEnv());

        $this->prepareClient();
    }

    protected function setEnv()
    {
        if ($this->moip->getEnv() == 'SANDBOX') {
            return 'https://sandbox.moip.com.br';
        }

        return 'https://api.moip.com.br';
    }

    /**
     * Get Moip
     *
     * @return \Softpampa\Moip\Moip
     */
    public function getMoip()
    {
        return $this->moip;
    }

    /**
     * Get Moip client
     *
     * @return \Softpampa\Moip\MoipClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Prepare client for requests
     *
     * @return void
     */
    protected function prepareClient()
    {
        $this->client->setPath($this->path)
                     ->setVersion($this->version);
    }

}
