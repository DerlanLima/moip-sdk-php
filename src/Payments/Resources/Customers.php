<?php

/**
 * Moip Subscription Customers API
 *
 * @since 0.0.1
 * @see http://dev.moip.com.br/referencia-api/#clientes Official Documentation
 * @author Nícolas Luís Huber <nicolasluishuber@gmail.com>
 */

namespace Softpampa\Moip\Payments\Resources;

use DateTime;
use stdClass;
use UnexpectedValueException;
use Softpampa\Moip\MoipResource;
use Softpampa\Moip\Payments\Events\CustomersEvent;

class Customers extends MoipResource {

    /**
     * Address type billing
     *
     * @const string
     */
    const ADDRESS_BILLING = 'BILLING';

    /**
     * Address type shipping
     *
     * @const string
     */
    const ADDRESS_SHIPPING = 'SHIPPING';

    /**
     * Default country
     *
     * @const string
     */
    const ADDRESS_COUNTRY = 'BRA';

    /**
     * Default document type
     *
     * @const string
     */
    const TAX_DOCUMENT = 'CPF';

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
     * @param  int  $customer_id
     * @return $this
     */
    public function find($customer_id)
    {
        return $this->populate($this->client->get('/{customer_id}', [$customer_id]));
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

        $this->populate($response);

        if (! $response->hasErrors()) {
            $this->event->dispatch('CLIENT.CREATE', new CustomersEvent($this->data));
        }

        return $this;
    }

    /**
     * Set your own id
     *
     * @param  int  $ownId
     * @return $this
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

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
     * Set customer birth date
     *
     * @param  string  $date
     * @return $this
     */
    public function setBirthdate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }

        $this->data->birthDate = $date;

        return $this;
    }

    /**
     * Set customer phone
     *
     * @param  int  $ddi
     * @param  int  $ddd
     * @param  int  $number
     * @return $this
     */
    public function setPhone($ddd, $number, $ddi = 55)
    {
        $this->data->phone = new stdClass();
        $this->data->phone->countryCode = $ddi;
        $this->data->phone->areaCode = $ddd;
        $this->data->phone->number = $number;

        return $this;
    }

    /**
     * Set customer tax document
     *
     * @param  int  $number
     * @param  string  $type
     * @return $this
     */
    public function setTaxDocument($number, $type = self::TAX_DOCUMENT)
    {
        $this->data->taxDocument = new stdClass();
        $this->data->taxDocument->type = $type;
        $this->data->taxDocument->number = $number;

        return $this;
    }

    /**
     * Set customer address
     *
     * @param  string  $type
     * @param  string  $street
     * @param  string  $number
     * @param  string  $complement
     * @param  string  $district
     * @param  string  $city
     * @param  string  $state
     * @param  string  $zipCode
     * @param  string  $country
     * @return $this
     */
    public function addAddress($type = self::ADDRESS_BILLING, $street, $number, $complement, $district, $city, $state, $zipCode, $country = self::ADDRESS_COUNTRY)
    {
        $address = new stdClass();
        $address->street = $street;
        $address->streetNumber = $number;
        $address->complement = $complement;
        $address->district = $district;
        $address->city = $city;
        $address->state = $state;
        $address->country = $country;
        $address->zipCode = $zipCode;

        switch ($type) {
            case self::ADDRESS_BILLING:
                $this->data->billingAddress = $address;
                break;
            case self::ADDRESS_SHIPPING:
                $this->data->shippingAddress = $address;
                break;
            default:
                throw new UnexpectedValueException(sprintf('%s não é um tipo de endereço válido', $type));
        }

        return $this;
    }

    public function addNewCreditCard($expirationMonth, $expirationYear, $number, $cvc, Customers $holder)
    {
        $
        $this->data = new stdClass;

    }

}
