<?php

/**
 * Moip PHP SDK
 *
 * @version 1.0.0
 * @see http://dev.moip.com.br/assinaturas-api/ Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip;

use Softpampa\Moip\MoipAuth;
use Softpampa\Moip\MoipHttpClient;
use Softpampa\Moip\Payments\PaymentApi;
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
     * @var  ḾoipHttpClient  $httpClient  Moip HTTP Client
     */
    protected $httpClient;

    /**
     * Constructor.
     *
     * @param  MoipAuth  $auth  Moip Authentication
     * @param  string  $environment  Moip Environment
     */
    public function __construct(MoipAuth $auth, $environment = self::SANDBOX)
    {
        $this->httpClient = new MoipHttpClient($auth, $environment);
    }

    /**
     * Get Http Client.
     *
     * @return ḾoipHttpClient
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
     * Moip Subscription
     *
     * @return Softpampa\Subscriptions\PaymentApi
     */
    public function payments()
    {
        return new PaymentApi($this);
    }

}
