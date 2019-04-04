<?php

namespace Pascal\Router;

class Binding
{

    /**
     * @var string
     */
    private $method;

    /**
     * @var \Pascal\Router\Route
     */
    private $route;

    /**
     * @var \Pascal\Router\Action
     */
    private $action;

    /**
     * @param string                $method
     * @param \Pascal\Router\Route  $route
     * @param \Pascal\Router\Action $action
     */
    public function __construct(string $method, Route $route, Action $action)
    {
        $this->method = $method;
        $this->route = $route;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return \Pascal\Router\Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @param \Pascal\Router\RouteArguments $parseArguments
     *
     * @return string
     */
    public function callAction(RouteArguments $parseArguments): string
    {
        return $this->action->call($parseArguments);
    }
}
