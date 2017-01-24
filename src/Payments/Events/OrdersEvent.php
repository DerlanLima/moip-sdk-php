<?php

namespace Softpampa\Moip\Payments\Events;

use stdClass;
use Symfony\Component\EventDispatcher\Event;

class OrdersEvent extends Event
{

    /**
     * @var  stdClass  $order
     */
    public $order;

    /**
     * Constructor.
     *
     * @param  stdClass  $order
     */
    public function __construct(stdClass $order)
    {
        $this->order = $order;
    }
}
