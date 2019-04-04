<?php

namespace Pascal\EventSubscriber\Tests;

use Pascal\EventSubscriber\Event\EventInterface;
use Pascal\EventSubscriber\EventSubscriber\EventSubscriber;
use PHPUnit\Framework\TestCase;

class EventSubscriberTest extends TestCase
{

    /** @test */
    public function subscriber_handles_event_it_is_subscribed_to()
    {
        $event = new EventMock;

        $eventSubscriber = SimpleEventSubscriber::createSubscriberFromEvent($event);

        self::assertEquals('event handled!', $eventSubscriber->handle($event));
    }

    /** @test */
    public function subscriber_does_not_handle_event_it_is_not_subscribed_to()
    {
        $event = new EventMock;

        $eventSubscriber = SimpleEventSubscriber::createSubscriberFromEvent($event);

        $anotherEvent = new AnotherEventMock;

        self::assertEquals(null, $eventSubscriber->handle($anotherEvent));
    }

}

class EventMock implements EventInterface
{
    //
}

class AnotherEventMock implements EventInterface
{
    //
}

class SimpleEventSubscriber extends EventSubscriber
{

    /**
     * @param EventInterface $event
     * @return mixed
     */
    public function handleSubscribedEvent(EventInterface $event)
    {
        return 'event handled!';
    }
}