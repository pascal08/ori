<?php

namespace Pascal\EventSubscriber\EventSubscriber;

use Pascal\EventSubscriber\Event\EventInterface;

interface EventSubscriberInterface
{

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event);
}
