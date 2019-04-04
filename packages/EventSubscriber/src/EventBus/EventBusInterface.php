<?php

namespace Pascal\EventSubscriber\EventBus;

use Pascal\EventSubscriber\Event\EventInterface;
use Pascal\EventSubscriber\EventSubscriber\EventSubscriberInterface;

interface EventBusInterface
{

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function publish(EventInterface $event);

    /**
     * @param EventSubscriberInterface $eventSubscriber
     */
    public function attach(EventSubscriberInterface $eventSubscriber): void;
}
