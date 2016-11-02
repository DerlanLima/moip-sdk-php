<?php

namespace Softpampa\Moip\Helpers;

use stdClass;

class TaxDocument extends SharedObjects {

    const TYPE = 'CPF';

    protected $number;

    protected $type;

    public function __construct($number, $type = self::TYPE)
    {
        $this->number = $number;
        $this->type = $type;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    protected function prepareDataForPayments()
    {
        $this->data = new stdClass;
        $this->data->type = $this->type;
        $this->data->number = $this->number;
    }

    protected function prepareDataForSubscriptions()
    {
        $this->data = $this->number;
    }

}
