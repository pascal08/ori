<?php

namespace Pascal\Router;

class ActionFactory
{

    /**
     * @param callable $callable
     *
     * @return \Pascal\Router\Action
     */
    public function fromCallable(callable $callable): Action
    {
        return new Action($callable);
    }
}
