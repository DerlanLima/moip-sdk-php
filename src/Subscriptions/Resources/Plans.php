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
use Illuminate\Support\Collection;

class Plans extends MoipResource {

    /**
     * @var  string  INTERVAL_MONTH  Interval in months
     */
    const INTERVAL_MONTH = 'MONTH';

    /**
     * @var  string  INTERVAL_DAY  Interval in days
     */
    const INTERVAL_DAY = 'DAY';

    /**
     * @var  string  INTERVAL_YEAR  Interval in years
     */
    const INTERVAL_YEAR = 'YEAR';

    /**
     * @var  string  PAYMNET_BOLETO  Boleto payment method
     */
    const PAYMNET_BOLETO = 'BOLETO';

    /**
     * @var  string  PAYMNET_CREDIT_CARD  Credit card payment method
     */
    const PAYMNET_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * @var  string  PAYMNET_ALL  All payments methods
     */
    const PAYMNET_ALL = 'ALL';

    /**
     * @var  string  STATUS_INACTIVE  Status inactive
     */
    const STATUS_INACTIVE = 'INACTIVE';

    /**
     * @var  string  STATUS_INACTIVE  Status active
     */
    const STATUS_ACTIVE = 'ACTIVE';

    /**
     * @var  string  $path
     */
    protected $path = 'plans';

    /**
     * Get all plans
     *
     * @return Collection
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
        return $this->populate($this->client->get('/{code}', ['code' => $code]));
    }

    /**
     * Save a plan
     *
     * @param  int  $code
     * @return $this
     */
    public function save()
    {
        $this->client->put('/{id}', ['id' => $this->data->code], $this->data);

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
        $this->client->put('/{code}', ['code' => $code], $data);

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
        if (!$data) {
            $data = $this->data;
        } else {
            $this->populate($data);
        }

        $this->client->post('', [], $data);

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
        if (!$code) {
            $code = $this->data->code;
        }

        $this->client->put('/{code}/activate', ['code' => $code]);

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
        if (!$code) {
            $code = $this->data->code;
        }

        $this->client->put('/{code}/inactivate', ['code' => $code]);

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
        $this->data->amount = $amount;

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
     * @param  int  $setup_fee
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
     * Set plan interval
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
    public function setStatus($status = 'ACTIVE')
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
    public function setPaymentMethod($paymentMethod = self::PAYMNET_CREDIT_CARD)
    {
        $this->data->payment_method = $paymentMethod;

        return $this;
    }

}
