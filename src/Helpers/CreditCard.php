<?php

namespace Softpampa\Moip\Helpers;

use stdClass;
use Softpampa\Moip\Contracts\Holder;

class CreditCard extends SharedObjects
{

    protected $expirationMonth;

    protected $expirationYear;

    protected $number;

    protected $cvc;

    protected $holder;

    public function __construct($expirationMonth, $expirationYear, $number, $cvc, Holder $holder)
    {
        $this->expirationMonth = $expirationMonth;
        $this->expirationYear = $expirationYear;
        $this->number = $number;
        $this->cvc = $cvc;
        $this->holder = $holder;
    }

    public function setExpirationMonth($expirationMonth)
    {
        $this->expirationMonth = $expirationMonth;

        return $this;
    }

    public function setExpirationYear($expirationYear)
    {
        $this->expirationYear = $expirationYear;

        return $this;
    }

    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    public function setCvc($cvc)
    {
        $this->cvc = $cvc;

        return $this;
    }

    public function setHolder(Holder $holder)
    {
        $this->holder = $holder;

        return $this;
    }

    protected function prepareDataForPayments()
    {
        $this->data = new stdClass;
        $this->data->expirationMonth = $this->expirationMonth;
        $this->data->expirationYear = $this->expirationYear;
        $this->data->number = $this->number;
        $this->data->cvc = $this->cvc;
        $this->data->holder = $this->holder->getData();
    }

    protected function prepareDataForSubscriptions()
    {
        $this->data = new stdClass;
        $this->data->expirationMonth = $this->expirationMonth;
        $this->data->expirationYear = $this->expirationYear;
        $this->data->number = $this->number;
        $this->data->holder_name = $this->holder->getData()->fullname;
    }
}
