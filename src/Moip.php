<?php

namespace Softpampa\Moip;

use Softpampa\Moip\MoipHttpClient;
use Softpampa\Moip\Payments\PaymentApi;
use Softpampa\Moip\Contracts\MoipAuthentication;
use Softpampa\Moip\Subscriptions\SubscriptionApi;

class Moip {

    /**
     * @const  string  Moip Production base URI
     */
    const PRODUCTION = 'https://api.moip.com.br';

    /**
     * @const  string  Moip Sandbox base URI
     */
    const SANDBOX = 'https://sandbox.moip.com.br';

    /**
     * @var  MoipHttpClient  $httpClient  Moip HTTP Client
     */
    protected $httpClient;

    /**
     * Constructor.
     *
     * @param  MoipAuth  $auth  Moip Authentication
     * @param  string  $environment  Moip Environment
     */
    public function __construct(MoipAuthentication $auth, $environment = self::SANDBOX, $options = [])
    {
        $this->httpClient = new MoipHttpClient($auth, $environment, $options);
    }

    /**
     * Get Http Client.
     *
     * @return MoipHttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * Moip Subscription
     *
     * @return Softpampa\Subscriptions\SubscriptionApi
     */
    public function subscriptions()
    {
        return new SubscriptionApi($this);
    }

    /**
     * Moip Payments
     *
     * @return Softpampa\Subscriptions\PaymentApi
     */
    public function payments()
    {
        return new PaymentApi($this);
    }

}
