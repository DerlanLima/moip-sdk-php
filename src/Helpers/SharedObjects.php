<?php

namespace Softpampa\Moip\Helpers;

use Softpampa\Moip\Moip;

abstract class SharedObjects
{

    protected $data;

    protected $context;

    abstract protected function prepareDataForPayments();

    abstract protected function prepareDataForSubscriptions();

    public function setContext($context)
    {
        $this->context = $context;
    }

    protected function prepareData()
    {
        if ($this->context == Moip::PAYMENT) {
            $this->prepareDataForPayments();
        } else {
            $this->prepareDataForSubscriptions();
        }
    }

    public function getData()
    {
        $this->prepareData();

        return $this->data;
    }
}
