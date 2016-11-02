<?php

namespace Softpampa\Moip\Helpers;

use stdClass;

class Phone extends SharedObjects {

    const COUNTRY_CODE = 55;

    protected $areaCode;

    protected $number;

    protected $countryCode;

    public function __construct($areaCode, $number, $countryCode = self::COUNTRY_CODE)
    {
        $this->areaCode = $areaCode;
        $this->number = $number;
        $this->countryCode = $countryCode;
    }

    public function setAreaCode($areaCode)
    {
        $this->areaCode = $areaCode;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function setCountryCode($countryCode)
    {
        $this->number = $countryCode;
    }

    protected function prepareDataForSubscriptions()
    {
        $this->data = new stdClass;
        $this->data->phone_area_code = $this->areaCode;
        $this->data->phone_number = $this->number;
    }

    protected function prepareDataForPayments()
    {
        $this->data = new stdClass;
        $this->data->countryCode = $this->countryCode;
        $this->data->areaCode = $this->areaCode;
        $this->data->number = $this->number;
    }

}
