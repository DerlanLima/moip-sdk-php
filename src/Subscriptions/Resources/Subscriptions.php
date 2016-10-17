<?php

/**
 * Moip Subscription Plans API
 *
 * @since 1.0.0
 * @see http://dev.moip.com.br/assinaturas-api/#planos Official Documentation Plans
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use Softpampa\Moip\MoipResource;

class Subscriptions extends MoipResource {

    /**
     * @var  string  $path
     */
    protected $path = 'subscriptions';

    /**
     * Get all subscriptions
     *
     * @return Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->httpClient->get()->getResults();
    }

    /**
     * Find a subscription
     *
     * @param  int  $code
     * @return $this
     */
    public function find($code)
    {
        return $this->populate($this->httpClient->get('/{code}', ['code' => $code]));
    }

    /**
     * Save a subscription
     *
     * @param  int  $code
     * @return $this
     */
    public function save()
    {
        $this->httpResponse = $this->httpClient->put('/{id}', ['id' => $this->data->code], $this->data);

        return $this;
    }

    /**
     * Suspend a subscription
     *
     * @return $this
     */
    public function suspend()
    {
        $this->httpResponse = $this->httpClient->put('/{id}/suspend', ['id' => $this->data->code], $this->data);

        return $this;
    }

    /**
     * Activate a subscription
     *
     * @return $this
     */
    public function activate()
    {
        $this->httpResponse = $this->httpClient->put('/{id}/activate', ['id' => $this->data->code], $this->data);

        return $this;
    }

    /**
     * Cancel a subscription
     *
     * @return $this
     */
    public function cancel()
    {
        $this->httpResponse = $this->httpClient->put('/{id}/cancel', ['id' => $this->data->code], $this->data);

        return $this;
    }

    /**
     * Subscription invoices
     *
     * @return $this
     */
    public function invoices()
    {
        $this->httpResponse = $this->httpClient->get('/{id}/invoices', ['id' => $this->data->code], $this->data)->setResource('invoices');

        return $this->httpResponse->getResults();
    }

    /**
     * Edit a subscription
     *
     * @param  int  $code
     * @param  array  $data
     * @return $this
     */
    public function edit($code, $data)
    {
        $this->httpResponse = $this->httpClient->put('/{code}', ['code' => $code], $data);

        return $this;
    }

    /**
     * Create a subscription
     *
     * @param  array  $data
     * @return $this
     */
    public function create($data = [])
    {
        if (!$data) {
            $data = $this->data;
        } else {
            $this->populate($data);
        }

        $this->httpResponse = $this->httpClient->post('', [], $data);

        return $this;
    }

    public function setCode($code)
    {
        $this->data->code = $code;

        return $this;
    }

    public function setAmount($amount)
    {
        $this->data->amount = $amount;

        return $this;
    }

    public function setPlan(Plans $plan)
    {
        $this->data->plan = $plan->jsonSerialize();

        return $this;
    }

    public function setNewCustomer(Customers $customer)
    {
        $this->httpClient->addQueryString([
            'new_customer' => 'true'
        ]);

        $this->data->customer = $customer->jsonSerialize();

        return $this;
    }

    public function setCustomer(Customers $customer)
    {
        $this->httpClient->addQueryString([
            'new_customer' => 'false'
        ]);

        $this->data->customer = $customer->jsonSerialize();

        return $this;
    }

}
