<?php

/**
 * Moip Subscription API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions;

use Softpampa\Moip\Api;
use Softpampa\Moip\Subscriptions\Resources\Plans;
use Softpampa\Moip\Subscriptions\Resources\Invoices;
use Softpampa\Moip\Subscriptions\Resources\Payments;
use Softpampa\Moip\Subscriptions\Resources\Customers;
use Softpampa\Moip\Subscriptions\Resources\Subscriptions;

class SubscriptionApi extends Api {

    /**
     * @var  string  Moip API Version
     */
    protected $version = 'v1';

    /**
     * @var  string  Moip base URI
     */
    protected $uri = 'assinaturas';

    /**
     * Plans API
     *
     * @return Softpampa\Moip\Resources\Plans
     */
    public function plans()
    {
        return new Plans($this->client);
    }

    /**
     * Customers API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function customers()
    {
        return new Customers($this->client);
    }

    /**
     * Subscriptions API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function subscriptions()
    {
        return new Subscriptions($this->client);
    }

    /**
     * Invoices API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function invoices()
    {
        return new Invoices($this->client);
    }

    /**
     * Payments API
     *
     * @return Softpampa\Moip\Resources\Customers
     */
    public function payments()
    {
        return new Payments($this->client);
    }

}
