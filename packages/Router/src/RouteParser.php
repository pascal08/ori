<?php

namespace Pascal\Router;

class RouteParser
{

    /**
     * @var \Pascal\Router\RouteParameterFilterInterface
     */
    private $routeParameterFilter;

    /**
     * @param \Pascal\Router\RouteParameterFilterInterface $routeParameterFilter
     */
    public function __construct(RouteParameterFilterInterface $routeParameterFilter)
    {
        $this->routeParameterFilter = $routeParameterFilter;
    }

    /**
     * @param \Pascal\Router\Route $route
     * @param string               $uri
     *
     * @return bool
     */
    public function isMatching(Route $route, string $uri): bool
    {
        if (!$this->samePathLength($route, $uri)) {
            return false;
        }

        $mappedRoute = $this->mapUriToRoute($route, $uri);

        foreach ($mappedRoute as $key => $value) {
            $keyLength = strlen($key);
            if ($keyLength > 0 && !$this->isUriVariable($key) && $key !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Pascal\Router\Route $route
     * @param string               $uri
     *
     * @return \Pascal\Router\RouteArguments
     */
    public function parseArguments(Route $route, string $uri): RouteArguments
    {
        if (!$this->isMatching($route, $uri)) {
            throw new CouldNotParseRouteException('Route and uri are not of the same path length.');
        }

        return new RouteArguments(
            $this->routeParameterFilter->filter(
                $this->mapUriToRoute($route, $uri)
            )
        );
    }

    /**
     * @param \Pascal\Router\Route $route
     * @param string               $uri
     *
     * @return bool
     */
    private function samePathLength(Route $route, string $uri): bool
    {
        return count($this->getPathParts((string)$route)) == count($this->getPathParts($uri));
    }

    /**
     * @param \Pascal\Router\Route $route
     * @param string               $uri
     *
     * @return array
     */
    private function mapUriToRoute(Route $route, string $uri): array
    {
        $map = array_combine($this->getPathParts((string)$route), $this->getPathParts($uri));

        if (is_array($map)) {
            return $map;
        }
        
        throw new CouldNotParseRouteException('Route and uri are not of the same path length.');
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function isUriVariable(string $key): bool
    {
        return strlen(trim($key, '{}')) !== strlen($key);
    }

    /**
     * @param string $route
     *
     * @return array
     */
    private function getPathParts(string $route): array
    {
        return explode('/', $route);
    }
}
