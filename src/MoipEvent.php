<?php

namespace Softpampa\Moip;

use Symfony\Component\EventDispatcher\EventDispatcher;

class MoipEvent
{

    /**
     * Event dispatcher
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * List of events
     *
     * @var array
     */
    protected $eventsDispatchers = [
        'PLAN.CREATE',
        'PLAN.UPDATE',
        'PLAN.ACTIVATED',
        'PLAN.INACTIVATED',
        'CUSTOMER.CREATED',
        'CUSTOMER.UPDATE',
        'SUBSCRIPTION.CREATED',
        'SUBSCRIPTION.UPDATED',
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
     * @param  mixed  $object
     * @param  string  $method
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
     * @param  mixed  $object
     * @return $this
     */
    public function dispatch($eventName, $object)
    {
        $this->dispatcher->dispatch($eventName, $object);

        return $this;
    }

    /**
     * Get Symfony event dispatcher
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }
}
