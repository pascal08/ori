<?php

namespace Pascal\Router;

interface ActionInterface
{

    /**
     * @param \Pascal\Router\RouteArguments $routeArguments
     *
     * @return string
     */
    public function call(RouteArguments $routeArguments): string;

    /**
     * @return callable
     */
    public function getCallable(): callable;
}
