<?php

namespace Softpampa\Moip;

use Softpampa\Moip\MoipClient;
use Softpampa\Moip\Payments\PaymentApi;
use Softpampa\Moip\Contracts\Authenticatable;
use Softpampa\Moip\Subscriptions\SubscriptionApi;
use Softpampa\Moip\Preferences\PreferencesApi;

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
     * @var  MoipClient  $client  Moip HTTP Client
     */
    protected $client;

    /**
     * @var  MoipEvent  $event  Moip Event Dispatcher
     */
    protected $event;

    /**
     * Constructor.
     *
     * @param  MoipAuth  $auth  Moip authentication
     * @param  string  $environment  Moip environment
     * @param  array  $options  Moip defaults pptions
     */
    public function __construct(Authenticatable $auth, $environment = self::SANDBOX, $options = [])
    {
        $this->event = new MoipEvent;
        $this->client = new MoipClient($auth, $environment, $options);
    }

    /**
     * Get Moip Events
     *
     * @return MoipEvents
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get Http Client.
     *
     * @return MoipClient
     */
    public function getClient()
    {
        return $this->client;
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

    public function preferences()
    {
        return new PreferencesApi($this);
    }

}
