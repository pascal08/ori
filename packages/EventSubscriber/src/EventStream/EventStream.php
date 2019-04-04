<?php

namespace Pascal\EventSubscriber\EventStream;

class EventStream
{

    /**
     * @var mixed[]
     */
    protected $eventResults;

    /**
     * @param mixed $eventResults
     * @return EventStream
     */
    public static function createFromEventResults($eventResults)
    {
        $eventStream = new static;

        $eventStream->addEventResults($eventResults);

        return $eventStream;
    }

    /**
     * @param mixed $eventResults
     */
    protected function addEventResults($eventResults)
    {
        foreach ($eventResults as $eventResult) {
            $this->addEventResult($eventResult);
        }
    }

    /**
     * @param mixed $eventResult
     */
    protected function addEventResult($eventResult)
    {
        $this->eventResults[] = $eventResult;
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->eventResults);
    }
}
