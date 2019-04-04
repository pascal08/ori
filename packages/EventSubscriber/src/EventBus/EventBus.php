<?php

namespace Pascal\EventSubscriber\EventBus;

use Pascal\EventSubscriber\Event\EventInterface;
use Pascal\EventSubscriber\EventStream\EventStream;
use Pascal\EventSubscriber\EventSubscriber\EventSubscriberInterface;

class EventBus implements EventBusInterface
{

    /**
     * @var EventSubscriberInterface[]|null
     */
    protected $eventSubscribers;

    /**
     * @param EventInterface $event
     * @return EventStream
     */
    public function publish(EventInterface $event): EventStream
    {
        $eventResults = [];

        foreach ((array)$this->getListenersInAttachedOrder() as $eventSubscriber) {
            $eventResults[] = $eventSubscriber->handle($event);
        }

        return EventStream::createFromEventResults($eventResults);
    }

    /**
     * @return EventSubscriberInterface[]|null
     */
    protected function getListenersInAttachedOrder(): ?array
    {
        return array_reverse((array)$this->eventSubscribers);
    }

    /**
     * @param EventSubscriberInterface $eventSubscriber
     */
    public function attach(EventSubscriberInterface $eventSubscriber): void
    {
        $this->eventSubscribers[] = $eventSubscriber;
    }
}
