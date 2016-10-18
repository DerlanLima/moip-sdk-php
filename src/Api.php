<?php

namespace Softpampa\Moip;

abstract class Api {

    /**
     * @var  Moip  $moip  Moip API
     */
    protected $moip;

    /**
     * @var  MoipHttpClient  $client  Http Client
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param  Moip  $moip  Moip API
     */
    public function __construct(Moip $moip)
    {
        $this->moip = $moip;
        $this->client = $this->moip->getHttpClient();

        $this->prepareClient();
    }

    /**
     * Prepare client for requests
     *
     * @return void
     */
    protected function prepareClient()
    {
        $this->client->setUri($this->uri)->setVersion($this->version);
    }

}
