<?php

namespace Softpampa\Moip\Helpers;

use stdClass;

class Address extends SharedObjects {

    const ADDRESS_COUNTRY = 'BRA';

    protected $street;

    protected $number;

    protected $complement;

    protected $district;

    protected $city;

    protected $state;

    protected $zipCode;

    protected $country;

    /**
     * Constructor
     *
     * @param [type] $street
     * @param [type] $number
     * @param [type] $complement
     * @param [type] $district
     * @param [type] $city
     * @param [type] $state
     * @param [type] $zipCode
     * @param [type] $country
     */
    public function __construct($street, $number, $complement, $district, $city, $state, $zipCode, $country = self::ADDRESS_COUNTRY)
    {
        $this->street = $street;
        $this->number = $number;
        $this->complement = $complement;
        $this->district = $district;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->country = $country;
    }

    protected function prepareData()
    {
        $address = new stdClass;
        $address->street = $this->street;
        $address->complement = $this->complement;
        $address->district = $this->district;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->country = $this->country;

        $this->data = $address;

        parent::prepareData();
    }

    protected function prepareDataForPayments()
    {
        $this->data->streetNumber = $this->number;
        $this->data->zipCode = $this->zipCode;
    }

    protected function prepareDataForSubscriptions()
    {
        $this->data->number = $this->number;
        $this->data->zipcode = $this->zipCode;
    }

}
