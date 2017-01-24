<?php

/**
 * Moip Subscription Plans API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api/#planos Official Documentation Plans
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use stdClass;
use Softpampa\Moip\MoipResource;
use Softpampa\Moip\Subscriptions\Events\PlansEvent;

class Plans extends MoipResource
{

    /**
     * Interval in months
     *
     * @var string
     */
    const INTERVAL_MONTH = 'MONTH';

    /**
     * Interval in days
     *
     * @var string
     */
    const INTERVAL_DAY = 'DAY';

    /**
     * Interval in years
     *
     * @var string
     */
    const INTERVAL_YEAR = 'YEAR';

    /**
     * Boleto payment method
     *
     * @var string
     */
    const PAYMENT_BOLETO = 'BOLETO';

    /**
     * Credit card payment method
     *
     * @var string
     */
    const PAYMENT_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * All payments methods
     *
     * @var string
     */
    const PAYMENT_ALL = 'ALL';

    /**
     * Status inactive
     *
     * @var string
     */
    const STATUS_INACTIVE = 'INACTIVE';

    /**
     * Status active
     *
     * @var string
     */
    const STATUS_ACTIVE = 'ACTIVE';

    /**
     * Resource name
     *
     * @var string
     */
    protected $resource = 'plans';

    /**
     * Get all plans
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->client->get()->getResults();
    }

    /**
     * Find a plan
     *
     * @param  int  $code
     * @return $this
     */
    public function find($code)
    {
        return $this->populate($this->client->get('/{code}', [$code]));
    }

    /**
     * Save a plan
     *
     * @return $this
     */
    public function save()
    {
        $response = $this->client->put('/{id}', [$this->data->code], $this->data);

        if (! $response->hasErrors()) {
            $this->event->dispatch('PLAN.UPDATE', new PlansEvent($this->data));
        }

        return $this;
    }

    /**
     * Edit  a plan
     *
     * @param  int  $code
     * @param  array  $data
     * @return $this
     */
    public function edit($code, $data)
    {
        $this->client->put('/{code}', [$code], $data);

        return $this;
    }

    /**
     * Create a plan
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
            $this->event->dispatch('PLAN.CREATE', new PlansEvent($this->data));
        }

        return $this;
    }

    /**
     * Activate a plan
     *
     * @param  int  $code
     * @return $this
     */
    public function activate($code = null)
    {
        if (! $code) {
            $code = $this->data->code;
        }

        $response = $this->client->put('/{code}/activate', [$code]);

        if (! $response->hasErrors()) {
            $this->event->dispatch('PLAN.ACTIVATED', new PlansEvent($this->data));
        }

        return $this;
    }

    /**
     * Inactivate a plan
     *
     * @param  int  $code
     * @return $this
     */
    public function inactivate($code = null)
    {
        if (! $code) {
            $code = $this->data->code;
        }

        $response = $this->client->put('/{code}/inactivate', [$code]);

        if (! $response->hasErrors()) {
            $this->event->dispatch('PLAN.INACTIVATED', new PlansEvent($this->data));
        }

        return $this;
    }

    /**
     * Set plan code
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
     * Set plan amount
     *
     * @param  int  $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->data->amount = str_replace([','], [''], $amount) * 100;

        return $this;
    }

    /**
     * Set plan name
     *
     * @param  int  $name
     * @return $this
     */
    public function setName($name)
    {
        $this->data->name = $name;

        return $this;
    }

    /**
     * Set plan description
     *
     * @param  int  $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->data->description = $description;

        return $this;
    }

    /**
     * Set plan setup_fee
     *
     * @param  int  $setup_fee
     * @return $this
     */
    public function setSetupFee($setup_fee)
    {
        $this->data->setup_fee = $setup_fee;

        return $this;
    }

    /**
     * Set plan interval
     *
     * @param  string  $unit
     * @param  int  $length
     * @return $this
     */
    public function setInterval($unit = self::INTERVAL_MONTH, $length = 1)
    {
        $this->data->interval = new stdClass;
        $this->data->interval->unit = $unit;
        $this->data->interval->length = $length;

        return $this;
    }

    /**
     * Set plan billing cycles
     *
     * @param  int  $billingCycles
     * @return $this
     */
    public function setBillingCycles($billingCycles = null)
    {
        $this->data->billing_cycles = $billingCycles;

        return $this;
    }

    /**
     * Set plan trial
     *
     * @param  boolean  $enable
     * @param  int  $days
     * @param  boolean  $hold_setup_fee
     * @return $this
     */
    public function setTrial($enable = false, $days = null, $hold_setup_fee = false)
    {
        $this->data->trial = new stdClass;
        $this->data->trial->enabled = (boolean) $enable;
        $this->data->trial->days = $days;
        $this->data->trial->hold_setup_fee = (boolean) $hold_setup_fee;

        return $this;
    }

    /**
     * Set plan trial
     *
     * @param  string  $status
     * @return $this
     */
    public function setStatus($status = self::STATUS_ACTIVE)
    {
        $this->data->status = $status;

        return $this;
    }

    /**
     * Set maximum plan subscriptions
     *
     * @param  string  $maxQty
     * @return $this
     */
    public function setMaxQdy($maxQty = null)
    {
        $this->data->max_qty = $maxQty;

        return $this;
    }

    /**
     * Set plan trial
     *
     * @param  string  $paymentMethod
     * @return $this
     */
    public function setPaymentMethod($paymentMethod = self::PAYMENT_CREDIT_CARD)
    {
        $this->data->payment_method = $paymentMethod;

        return $this;
    }
}
