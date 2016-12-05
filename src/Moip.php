<?php

namespace Softpampa\Moip;

use Softpampa\Moip\Payments\PaymentApi;
use Softpampa\Moip\Contracts\Authenticatable;
use Softpampa\Moip\Subscriptions\SubscriptionApi;
use Softpampa\Moip\Preferences\PreferencesApi;

class Moip {

    /**
     * Moip Production base URI
     *
     * @const string
     */
    const PRODUCTION = 'PRODUCTION';

    /**
     * Moip Sandbox base URI
     *
     * @const string
     */
    const SANDBOX = 'SANDBOX';

    /**
     * Moip Payment
     *
     * @const string
     */
    const PAYMENT = 'PAYMENT';

    /**
     * Moip Subscription
     *
     * @const string
     */
    const SUBSCRIPTION = 'SUBSCRIPTION';

    /**
     * Moip auth
     *
     * @var \Softpampa\Moip\Contracts\Authenticatable
     */
    protected $auth;

    /**
     * Moip environment
     *
     * @return string
     */
    protected $environment;

    /**
     * Moip options
     *
     * @return array
     */
    protected $options;

    /**
     * Moip Event Dispatcher
     *
     * @var \Softpampa\Moip\MoipEvent
     */
    protected $event;

    /**
     * Constructor.
     *
     * @param  \Softpampa\Moip\Contracts\Authenticatable  $auth
     * @param  string  $environment
     * @param  array  $options
     */
    public function __construct(Authenticatable $auth, $environment = self::SANDBOX, $options = [])
    {
        $this->event = new MoipEvent;
        $this->auth = $auth;
        $this->options = $options;
        $this->environment = $environment;
    }

    /**
     * Get Moip event system
     *
     * @return \Softpampa\Moip\MoipEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get Moip auth
     *
     * @return \Softpampa\Moip\Contracts\Authenticatable
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * Get Moip environment
     *
     * @return string
     */
    public function getEnv()
    {
        return $this->environment;
    }

    /**
     * Moip Subscription
     *
     * @return \Softpampa\Moip\Subscriptions\SubscriptionApi
     */
    public function subscriptions()
    {
        return new SubscriptionApi($this);
    }

    /**
     * Moip Payments
     *
     * @return \Softpampa\Moip\Payments\PaymentApi
     */
    public function payments()
    {
        return new PaymentApi($this);
    }

}
