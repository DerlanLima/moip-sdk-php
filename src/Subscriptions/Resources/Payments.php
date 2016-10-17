<?php

/**
 * Moip Subscription Payments API
 *
 * @since 1.0.0
 * @see http://dev.moip.com.br/assinaturas-api/#assinantes Official Documentation Customers
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use Softpampa\Moip\MoipResource;

class Payments extends MoipResource {

    /**
     * @var  string  $path
     */
    protected $path = 'payments';

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

}
