<?php

/**
 * Moip Subscription Invoices API
 *
 * @since 1.0.0
 * @see http://dev.moip.com.br/assinaturas-api/#assinantes Official Documentation Customers
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
        $this->httpResponse = $this->populate($this->httpClient->get('/{id}', ['id' => $id]));

        return $this;
    }

    /**
     * Return all payments from a invoice
     *
     * @return $this
     */
    public function payments()
    {
        $this->httpResponse = $this->httpClient->get('/{id}/payments', ['id' => $this->data->id])->setResource('payments');

        return $this->httpResponse->getResults();
    }

}
