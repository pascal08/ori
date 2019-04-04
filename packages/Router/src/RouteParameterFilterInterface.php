<?php

namespace Pascal\Router;

interface RouteParameterFilterInterface
{

    /**
     * @param array $mappedRoute
     *
     * @return array
     *
     * @throws \Pascal\Router\CouldNotParseRouteException
     */
    public function filter(array $mappedRoute): array;
}
