<?php

namespace Pascal\Router;

class RouteParameterFilter implements RouteParameterFilterInterface
{

    /**
     * @param array $mappedRoute
     *
     * @return array
     */
    public function filter(array $mappedRoute): array
    {
        $parameters = array_filter($mappedRoute, function ($key) {
            return preg_match('/{[a-zA-Z0-9_]+}/', $key);
        }, ARRAY_FILTER_USE_KEY);

        $mappedParameters = [];

        foreach ($parameters as $key => $value) {
            $mappedParameters[trim($key, '{}')] = $value;
        }

        return $mappedParameters;
    }
}
