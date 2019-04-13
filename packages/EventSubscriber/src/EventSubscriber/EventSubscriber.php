<?php

namespace Pascal\EventSubscriber\EventSubscriber;

use Pascal\EventSubscriber\Event\EventInterface;

abstract class EventSubscriber implements EventSubscriberInterface
{

    /**
     * @var string
     */
    protected $subscribedTo;

    /**
     * EventSubscriber constructor.
     */
    private function __construct()
    {
        // Intentionally left empty
    }

    /**
     * @param EventInterface $event
     * @return EventSubscriber
     */
    public static function createSubscriberFromEvent(EventInterface $event): EventSubscriber
    {
        $subscriber = new static;

        $subscriber->subscribeTo($event);

        return $subscriber;
    }

    /**
     * @param EventInterface $event
     */
    private function subscribeTo(EventInterface $event): void
    {
        $this->subscribedTo = get_class($event);
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handle(EventInterface $event)
    {
        if ($this->isSubscribedTo($event)) {
            return $this->handleSubscribedEvent($event);
        }

        return null;
    }

    /**
     * @param EventInterface $event
     * @return bool
     */
    private function isSubscribedTo(EventInterface $event): bool
    {
        return $this->subscribedTo == get_class($event);
    }

    /**
     * @param EventInterface $event
     * @return mixed
     */
    abstract public function handleSubscribedEvent(EventInterface $event);
}
