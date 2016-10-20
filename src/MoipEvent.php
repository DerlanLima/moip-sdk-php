<?php

namespace Softpampa\Moip;

use Symfony\Component\EventDispatcher\EventDispatcher;

class MoipEvent {

    /**
     * @var  EventDispatcher  $dispatcher
     */
    protected $dispatcher;

    /**
     * @var  array  $eventsDispatchers
     */
    protected $eventsDispatchers = [
        'PLAN.CREATE',
        'PLAN.UPDATE',
        'PLAN.ACTIVATED',
        'PLAN.INACTIVATED',
        'CUSTOMER.CREATED',
        'CUSTOMER.UPDATE',
        'SUBSCRIPTION.CREATED',
        'SUBSCRIPTION.UPDATE',
        'SUBSCRIPTION.SUSPENDED',
        'SUBSCRIPTION.ACTIVATED',
        'SUBSCRIPTION.CANCELED',
        'CLIENT.CREATED',
        'CLIENT.UPDATE',
        'ORDER.CREATED',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dispatcher = new EventDispatcher;
    }

    /**
     * Add event listener
     *
     * @param  string  $eventName
     * @param Object $object
     * @param string $method
     * @return $this
     */
    public function addListener($eventName, $object, $method = null)
    {
        $this->dispatcher->addListener($eventName, [$object, $method]);

        return $this;
    }

    /**
     * Dispatch a event
     *
     * @param  string  $eventName
     * @param Object $object
     * @return $this
     */
    public function dispatch($eventName, $object)
    {
        $this->dispatcher->dispatch($eventName, $object);
    }

}
