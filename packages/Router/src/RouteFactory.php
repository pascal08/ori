<?php

namespace Pascal\Router;

class RouteFactory
{

    /**
     * @param string $route
     *
     * @return \Pascal\Router\Route
     */
    public function fromString(string $route): Route
    {
        return new Route($route);
    }
}
