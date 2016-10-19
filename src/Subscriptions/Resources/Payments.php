<?php

/**
 * Moip Subscription Payments API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api/#pagamentos Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use Softpampa\Moip\MoipResource;

class Payments extends MoipResource {

    /**
     * @var  string  $path
     */
    protected $resource = 'payments';

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

}
