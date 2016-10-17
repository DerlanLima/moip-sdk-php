<?php

/**
 * Moip Subscription Customers API
 *
 * @since 1.0.0
 * @see http://dev.moip.com.br/assinaturas-api/#assinantes Official Documentation Customers
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Subscriptions\Resources;

use stdClass;
use Illuminate\Support\Collection;
use Softpampa\Moip\MoipResource;

class Customers extends MoipResource {

    /**
     * @var  string  $path
     */
    protected $path = 'customers';

    /**
     * Get all customers
     *
     * @return Collection
     */
    public function all()
    {
        return $this->httpClient->get()->getResults();
    }

    /**
     * Find a customer
     *
     * @param  int  $code
     * @return $this
     */
    public function find($code)
    {
        return $this->populate($this->httpClient->get('/{code}', ['code' => $code]));
    }

    /**
     * Save a customer
     *
     * @param  int  $code
     * @return $this
     */
    public function save()
    {
        $this->httpResponse = $this->httpClient->put('/{code}', ['code' => $this->data->code], $this->data);

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
        if (!$data) {
            $data = $this->data;
        } else {
            $this->populate($data);
        }

        $this->httpResponse = $this->httpClient->post('', [], $data);

        return $this;
    }

    /**
     * Edit a customer
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
     * Update Billing info
     *
     * @return $this
     */
    public function updateBillingInfo()
    {
        $this->httpResponse = $this->httpClient->put('/{code}/billing_infos', ['code' => $this->data->code], $this->data->billing_info);

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
     * @param  int  $ddd
     * @param  int  $number
     * @return $this
     */
    public function setPhone($ddd, $number)
    {
        $this->data->phone_area_code = $ddd;
        $this->data->phone_number = $number;

        return $this;
    }

    /**
     * Set customer birthday
     *
     * @param  int  $day
     * @param  int  $month
     * @param  int  $year
     * @return $this
     */
    public function setBirthday($day, $month, $year)
    {
        $this->data->birthdate_day = $day;
        $this->data->birthdate_month = $month;
        $this->data->birthdate_year = $year;

        return $this;
    }

    /**
     * Set customer address
     *
     * @param  string  $street
     * @param  string  $number
     * @param  string  $complement
     * @param  string  $district
     * @param  string  $city
     * @param  string  $state
     * @param  string  $country
     * @param  string  $zipcode
     * @return $this
     */
    public function setAddress($street, $number, $complement, $district, $city, $state, $country, $zipcode)
    {
        $this->data->address = new stdClass;
        $this->data->address->street = $street;
        $this->data->address->number = $number;
        $this->data->address->complement = $complement;
        $this->data->address->district = $district;
        $this->data->address->city = $city;
        $this->data->address->state = $state;
        $this->data->address->country = $country;
        $this->data->address->zipcode = $zipcode;

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
        $this->data->billing_info = new stdClass;

        $creditCard = $this->data->billing_info->credit_card = new stdClass;
        $creditCard->holder_name = $holderName;
        $creditCard->number = $number;
        $creditCard->expiration_month = $expirationMonth;
        $creditCard->expiration_year = $expirationYear;

        return $this;
    }

}
