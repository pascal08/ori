<?php

namespace Pascal\Router;

class Action implements ActionInterface
{

    /**
     * @var callable
     */
    private $callable;

    /**
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @param \Pascal\Router\RouteArguments $routeArguments
     *
     * @return string
     */
    public function call(RouteArguments $routeArguments): string
    {
        return call_user_func_array($this->callable, $routeArguments->toArray());
    }
}
