<?php

/**
 * Moip Subscription API
 *
 * @since 1.0.0
 * @see http://dev.moip.com.br/assinaturas-api/#assinantes Official Documentation Customers
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions;

use Softpampa\Moip\Moip;
use Softpampa\Moip\Subscriptions\Resources\Customers;
use Softpampa\Moip\Subscriptions\Resources\Invoices;
use Softpampa\Moip\Subscriptions\Resources\Payments;
use Softpampa\Moip\Subscriptions\Resources\Plans;
use Softpampa\Moip\Subscriptions\Resources\Subscriptions;

class SubscriptionApi {

    /**
     * @const  string  Moip API Version
     */
    const VERSION = 'v1';

    /**
     * @const  string  Moip base URI
     */
    const URI = 'assinaturas';

    /**
     * @var  Softpampa\Moip\ḾoipHttpClient  $httpClient  HTTP Client
     */
    protected $httpClient;

    /**
     * @var  Moip  $moip  Moip API
     */
    protected $moip;

    /**
     * Constructor.
     *
     * @param  Moip  $moip  Moip API
     */
    public function __construct($moip)
    {
        $this->moip = $moip;
        $this->httpClient = $this->moip->getHttpClient();
        $this->httpClient->setUri(self::URI)->setVersion(self::VERSION);
    }

    /**
     * Plans API
     *
     * @return Softpampa\Moip\Resources\Plans
     */
    public function plans()
    {
        return new Plans($this->httpClient);
    }

    /**
     * Customers API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function customers()
    {
        return new Customers($this->httpClient);
    }

    /**
     * Subscriptions API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function subscriptions()
    {
        return new Subscriptions($this->httpClient);
    }

    /**
     * Invoices API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function invoices()
    {
        return new Invoices($this->httpClient);
    }

    /**
     * Payments API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function payments()
    {
        return new Payments($this->httpClient);
    }

}
