<?php

/**
 * Moip Subscription Customers API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/assinaturas-api/#assinantes Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use Softpampa\Moip\Helpers\Address;
use Softpampa\Moip\Helpers\Phone;
use Softpampa\Moip\Moip;
use stdClass;
use DateTime;
use Softpampa\Moip\MoipResource;
use Softpampa\Moip\Subscriptions\Events\CustomersEvent;

class Customers extends MoipResource
{

    /**
     * Resource name
     *
     * @var string
     */
    protected $resource = 'customers';

    /**
     * Get all customers
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->client->get()->getResults();
    }

    /**
     * Find a customer
     *
     * @param  int  $code
     * @return $this
     */
    public function find($code)
    {
        return $this->populate($this->client->get('/{code}', ['code' => $code]));
    }

    /**
     * Save a customer
     *
     * @return $this
     */
    public function save()
    {
        $response = $this->client->put('/{code}', ['code' => $this->data->code], $this->data);

        if (!$response->hasErrors()) {
            $this->event->dispatch('CUSTOMER.UPDATE', new CustomersEvent($this->data));
        }

        return $this;
    }

    /**
     * Create a customer
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

        if (! $response->hasErrors()) {
            $this->event->dispatch('CUSTOMER.CREATED', new CustomersEvent($this->data));
        }

        return $this;
    }

    /**
     * Edit a customer
     *
     * @param  int  $code
     * @param  array  $data
     * @return $this
     */
    public function edit($code, $data = [])
    {
        $this->client->put('/{code}', ['code' => $code], $data);

        return $this;
    }

    /**
     * Update Billing info
     *
     * @param  string  $code
     * @param  array  $data
     * @return $this
     */
    public function updateBillingInfo($code = null, $data = [])
    {
        $payload = [];

        if (! $code) {
            $code = $this->data->code;
        }

        if (! empty($data)) {
            $payload['credit_card'] = $data;
        } elseif (isset($this->data->billing_info)) {
            $payload = $this->data->billing_info;
        }

        $this->client->put('/{code}/billing_infos', [$code], $payload);

        return $this;
    }

    /**
     * Set customer code
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
     * Set customer fullname
     *
     * @param  string  $fullname
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->data->fullname = $fullname;

        return $this;
    }

    /**
     * Set customer email
     *
     * @param  string  $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->data->email = $email;

        return $this;
    }

    /**
     * Set customer CPF
     *
     * @param  string  $cpf
     * @return $this
     */
    public function setCpf($cpf)
    {
        $this->data->cpf = $cpf;

        return $this;
    }

    /**
     * Set customer phone
     *
     * @param  \Softpampa\Moip\Helpers\Phone  $phone
     * @return $this
     */
    public function setPhone(Phone $phone)
    {
        $phone->setContext(Moip::SUBSCRIPTION);
        $this->data = (object) array_merge((array) $this->data, (array) $phone->getData());

        return $this;
    }

    /**
     * Set customer birthday
     *
     * @param  string  $birthdate
     * @return $this
     */
    public function setBirthdate($birthdate)
    {
        $date = DateTime::createFromFormat('Y-m-d', $birthdate);

        $this->data->birthdate_day = $date->format('d');
        $this->data->birthdate_month = $date->format('m');
        $this->data->birthdate_year = $date->format('Y');

        return $this;
    }

    /**
     * Set customer address
     *
     * @param  \Softpampa\Moip\Helpers\Address  $address
     * @return $this
     */
    public function setAddress(Address $address)
    {
        $address->setContext(Moip::SUBSCRIPTION);
        $this->data->address = $address->getData();

        return $this;
    }

    /**
     * Set customer billing info
     *
     * @param  string  $holderName
     * @param  string  $number
     * @param  string  $expirationMonth
     * @param  string  $expirationYear
     * @return $this
     */
    public function setBillingInfo($holderName, $number, $expirationMonth, $expirationYear)
    {
        $this->client->addQueryString('new_vault', true);

        $this->data->billing_info = new stdClass;

        $creditCard = $this->data->billing_info->credit_card = new stdClass;
        $creditCard->holder_name = $holderName;
        $creditCard->number = $number;
        $creditCard->expiration_month = $expirationMonth;
        $creditCard->expiration_year = $expirationYear;

        return $this;
    }
}
