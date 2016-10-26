<?php

/**
 * Moip Payment API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/referencia-api Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Payments;

use Softpampa\Moip\MoipApi;
use Softpampa\Moip\Payments\Resources\Orders;
use Softpampa\Moip\Payments\Resources\Customers;
use Softpampa\Moip\Subscriptions\Resources\Payments;

class PaymentApi extends MoipApi {

    /**
     * @const  string  Moip API Version
     */
    protected $version = 'v2';

    /**
     * @const  string  Moip base URI
     */
    protected $uri = '';

    /**
     * Customers API
     *
     * @return Softpampa\Moip\Payments\Customers
     */
    public function customers()
    {
        return new Customers($this);
    }

    /**
     * Orders API
     *
     * @return Softpampa\Moip\Payments\Orders
     */
    public function orders()
    {
        return new Orders($this);
    }

    /**
     * Payments API
     *
     * @return Softpampa\Moip\Payments\Payments
     */
    public function payments()
    {
        return new Payments($this);
    }

}
