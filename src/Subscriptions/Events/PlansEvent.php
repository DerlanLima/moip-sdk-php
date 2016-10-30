<?php

namespace Softpampa\Moip\Subscriptions\Events;

use stdClass;
use Symfony\Component\EventDispatcher\Event;

class PlansEvent extends Event {

    /**
     * @var \stdClass
     */
    public $plan;

    /**
     * Constructor.
     *
     * @param  \stdClass  $plan
     */
    public function __construct(stdClass $plan)
    {
        $this->plan = $plan;
    }

}
