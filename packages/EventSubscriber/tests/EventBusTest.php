<?php

namespace Pascal\EventSubscriber\Tests;

use Pascal\EventSubscriber\EventBus\EventBus;
use Pascal\EventSubscriber\Event\EventInterface;
use Pascal\EventSubscriber\EventStream\EventStream;
use Pascal\EventSubscriber\EventSubscriber\EventSubscriber;
use PHPUnit\Framework\TestCase;

class EventBusTest extends TestCase
{


    /** @test */
    public function it_handles_an_event_when_it_has_a_subscriber_attached_that_it_subscribed_to_this_event()
    {
        $eventBus = new EventBus;

        $eventBus->attach(new EventSubscriberForEventMock);

        $eventStream = $eventBus->publish(new EventMock);

        self::assertEquals(EventStream::class, get_class($eventStream));

        self::assertEquals('EventMock handled by EventSubscriber.', $eventStream->pop());
    }

    /** @test */
    public function it_does_not_handle_events_when_it_has_no_subscribers_attached_that_are_not_subscribed_to_those_event()
    {
        $eventBus = new EventBus;

        $eventBus->attach(new EventSubscriberForEventMock);

        $eventStream = $eventBus->publish(new AnotherEventMock);

        self::assertEquals(null, $eventStream->pop());
    }

    /** @test */
    public function it_handles_multiple_event_when_it_has_subscribers_attached_that_it_subscribed_to_those_event()
    {
        $eventBus = new EventBus;

        $eventBus->attach(new EventSubscriberForEventMock);
        $eventBus->attach(new AnotherEventSubscriberForEventMock);

        $eventStream = $eventBus->publish(new EventMock);

        self::assertEquals('EventMock handled by EventSubscriber.', $eventStream->pop());
        self::assertEquals('EventMock handled by AnotherEventSubscriber.', $eventStream->pop());
    }
}

class EventSubscriberForEventMock extends EventSubscriber
{

    protected $subscribedTo = EventMock::class;

    public function __construct()
    {
        //
    }

    public function handleSubscribedEvent(EventInterface $event)
    {
        return 'EventMock handled by EventSubscriber.';
    }
}


class AnotherEventSubscriberForEventMock extends EventSubscriber
{

    protected $subscribedTo = EventMock::class;

    public function __construct()
    {
        //
    }

    public function handleSubscribedEvent(EventInterface $event)
    {
        return 'EventMock handled by AnotherEventSubscriber.';
    }
}
