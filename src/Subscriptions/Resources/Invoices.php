<?php

/**
 * Moip Subscription Invoices API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api/#faturas Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use Softpampa\Moip\MoipResource;

class Invoices extends MoipResource {

    /**
     * @var  string  $path
     */
    protected $path = 'invoices';

    /**
     * Find a invoice
     *
     * @param  int  $id
     * @return $this
     */
    public function find($id)
    {
        $this->populate($this->client->get('/{id}', ['id' => $id]));

        return $this;
    }

    /**
     * Return all payments from a invoice
     *
     * @return $this
     */
    public function payments()
    {
        return $this->client->get('/{id}/payments', ['id' => $this->data->id])->setResource('payments')->getResults();
    }

}
