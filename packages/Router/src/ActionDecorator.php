<?php

namespace Pascal\Router;

abstract class ActionDecorator implements ActionInterface
{

    /**
     * @var \Pascal\Router\ActionInterface
     */
    protected $action;

    /**
     * @param \Pascal\Router\ActionInterface $action
     */
    public function __construct(ActionInterface $action)
    {
        $this->action = $action;
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->action->getCallable();
    }
}
