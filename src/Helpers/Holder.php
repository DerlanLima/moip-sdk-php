<?php

namespace Softpampa\Moip\Helpers;

use stdClass;

class Holder extends SharedObjects implements \Softpampa\Moip\Contracts\Holder
{

    protected $fullName;

    protected $birthDate;

    protected $phone;

    protected $taxDocument;

    public function __construct($fullName, $birthDate, Phone $phone, TaxDocument $taxDocument)
    {
        $this->fullName = $fullName;
        $this->birthDate = $birthDate;
        $this->phone = $phone;
        $this->taxDocument = $taxDocument;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function setPhone(Phone $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function setTaxDocument(TaxDocument $taxDocument)
    {
        $this->taxDocument = $taxDocument;

        return $this;
    }

    protected function prepareDataForPayments()
    {
        $this->data = new stdClass;
        $this->data->fullname = $this->fullName;
        $this->data->birthdate = $this->birthDate;
        $this->data->taxDocument = $this->taxDocument->getData();
        $this->data->phone = $this->phone->getData();
    }

    protected function prepareDataForSubscriptions()
    {
        $this->data = $this->fullName;
    }
}
