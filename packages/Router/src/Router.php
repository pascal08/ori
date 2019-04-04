<?php

namespace Pascal\Router;

class Router
{

    /**
     * @var Binding[]
     */
    private $bindings = [];

    /**
     * @var \Pascal\Router\ActionFactory
     */
    private $actionFactory;

    /**
     * @var \Pascal\Router\RouteFactory
     */
    private $routeFactory;

    /**
     * @var \Pascal\Router\RouteParser
     */
    private $routeParser;

    /**
     * @param \Pascal\Router\ActionFactory $actionFactor
     * @param \Pascal\Router\RouteFactory  $routeFactory
     * @param \Pascal\Router\RouteParser   $routeParser
     */
    public function __construct(
        ActionFactory $actionFactor,
        RouteFactory $routeFactory,
        RouteParser $routeParser
    ) {
        $this->actionFactory = $actionFactor;
        $this->routeFactory = $routeFactory;
        $this->routeParser = $routeParser;
    }

    /**
     * @param string   $method
     * @param string   $route
     * @param callable $callable
     */
    public function bind(string $method, string $route, callable $callable)
    {
        $this->bindings[] = new Binding(
            $method,
            $this->routeFactory->fromString($route),
            $this->actionFactory->fromCallable($callable)
        );
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        foreach ($this->bindings as $binding) {
            $route = $binding->getRoute();

            if ($binding->getMethod() === $method && $this->routeParser->isMatching($route, $uri)) {
                return $binding->callAction($this->routeParser->parseArguments($route, $uri));
            }
        }

        throw new NoRouteFoundException('No route found for ' . $method . ' ' . $uri . '.');
    }
}
