<?php

/**
 * Moip Subscription Plans API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api/#assinaturas Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use DateTime;
use stdClass;
use Illuminate\Support\Collection;
use Softpampa\Moip\MoipResource;
use Softpampa\Moip\Subscriptions\Events\SubscriptionsEvent;

class Subscriptions extends MoipResource {

    /**
     * @const  string  METHOD_CREDIT_CARD  Payment method
     */
    const METHOD_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * @var  string  $path
     */
    protected $resource = 'subscriptions';

    /**
     * {@inheritdoc}
     */
    protected function initialize()
    {
        parent::initialize();

        $this->data->payment_method = self::METHOD_CREDIT_CARD;
    }

    /**
     * Get all subscriptions
     *
     * @return Collection
     */
    public function all()
    {
        return $this->client->get()->getResults();
    }

    /**
     * Find a subscription
     *
     * @param  int  $code
     * @return $this
     */
    public function find($code)
    {
        return $this->populate($this->client->get('/{code}', ['code' => $code]));
    }

    /**
     * Save a subscription
     *
     * @param  int  $code
     * @return $this
     */
    public function save()
    {
        $response = $this->client->put('/{id}', ['id' => $this->data->code], $this->data);

        if (! $response->hasErrors()) {
            $this->event->dispatch('SUBSCRIPTION.UPDATE', new SubscriptionsEvent($this->data));
        }

        return $this;
    }

    /**
     * Suspend a subscription
     *
     * @return $this
     */
    public function suspend($code = null)
    {
        if (! $code) {
            $code = $this->data->code;
        }

        $response = $this->client->put('/{id}/suspend', ['id' => $code]);

        if (! $response->hasErrors()) {
            $this->event->dispatch('SUBSCRIPTION.SUSPENDED', new SubscriptionsEvent($this->data));
        }

        return $this;
    }

    /**
     * Activate a subscription
     *
     * @return $this
     */
    public function activate($code = null)
    {
        if (! $code) {
            $code = $this->data->code;
        }

        $response = $this->client->put('/{id}/activate', ['id' => $code], $this->data);

        if (! $response->hasErrors()) {
            $this->event->dispatch('SUBSCRIPTION.ACTIVATED', new SubscriptionsEvent($this->data));
        }

        return $this;
    }

    /**
     * Cancel a subscription
     *
     * @return $this
     */
    public function cancel($code = null)
    {
        if (! $code) {
            $code = $this->data->code;
        }

        $response = $this->client->put('/{id}/cancel', ['id' => $code]);

        if (! $response->hasErrors()) {
            $this->event->dispatch('SUBSCRIPTION.CANCELED', new SubscriptionsEvent($this->data));
        }

        return $this;
    }

    /**
     * Subscription invoices
     *
     * @return $this
     */
    public function invoices($code = null)
    {
        if (! $code) {
            $code = $this->data->code;
        }

        return $this->client->get('/{id}/invoices', ['id' => $code])
                            ->setResource('invoices')
                            ->getResults();
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
        $this->client->put('/{code}', ['code' => $code], $data);

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
        if (! $data) {
            $data = $this->data;
        } else {
            $this->populate($data);
        }

        $response = $this->client->post('', [], $data);

        if (!$response->hasErrors()) {
            $this->event->dispatch('SUBSCRIPTION.UPDATE', new SubscriptionsEvent($this->data));
        }

        return $this;
    }

    /**
     * Set next invoice date
     *
     * @param  string  $date
     * @return $this
     */
    public function setNextInvoiceDate($date)
    {
        $date = DateTime::createFromFormat('Y-m-d', $date);

        $this->next_invoice_date = new stdClass();
        $this->next_invoice_date->day = $date->format('d');
        $this->next_invoice_date->month = $date->format('m');
        $this->next_invoice_date->year = $date->format('Y');

        return $this;
    }

    /**
     * Set Code
     *
     * @param  string  $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->data->code = $code;

        return $this;
    }

    /**
     * Set Amount
     *
     * @param  int  $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->data->amount = $amount;

        return $this;
    }

    /**
     * Set Plan
     *
     * @param  string|Plans  $plan
     * @return $this
     */
    public function setPlan($plan)
    {
        $this->data->plan = new stdClass;

        if ($plan instanceof Plans) {
            $code = $plan->code;
        } else {
            $code = $plan;
        }

        $this->data->plan->code = $code;

        return $this;
    }

    /**
     * Set New Customer
     *
     * @param  Customers  $customer
     * @return $this
     */
    public function setNewCustomer(Customers $customer)
    {
        $this->client->addQueryString([
            'new_customer' => 'true'
        ]);

        $this->data->customer = $customer->jsonSerialize();

        return $this;
    }

    /**
     * Set Customer
     *
     * @param  string|Customers  $customer
     * @return $this
     */
    public function setCustomer($customer)
    {
        $this->client->addQueryString([
            'new_customer' => 'false'
        ]);

        if ($customer instanceof Customers) {
            $code = $customer->code;
        } else {
            $code = $customer;
        }

        $this->data->customer = new stdClass;
        $this->data->customer->code = $code;

        return $this;
    }

}
