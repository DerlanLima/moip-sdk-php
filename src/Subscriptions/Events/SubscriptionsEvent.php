<?php

namespace Softpampa\Moip\Subscriptions\Events;

use stdClass;
use Symfony\Component\EventDispatcher\Event;

class SubscriptionsEvent extends Event
{

    /**
     * @var \stdClass
     */
    public $subscription;

    /**
     * Constructor.
     *
     * @param  \stdClass  $subscription
     */
    public function __construct(stdClass $subscription)
    {
        $this->subscription = $subscription;
    }
}
