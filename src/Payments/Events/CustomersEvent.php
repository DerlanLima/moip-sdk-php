<?php

namespace Softpampa\Moip\Payments\Events;

use stdClass;
use Symfony\Component\EventDispatcher\Event;

class CustomersEvent extends Event
{

    /**
     * @var  stdClass  $customer
     */
    public $customer;

    /**
     * Constructor.
     *
     * @param  stdClass  $customer
     */
    public function __construct(stdClass $customer)
    {
        $this->customer = $customer;
    }
}
