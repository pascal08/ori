<?php

namespace Pascal\Router;

use Psr\Container\ContainerInterface;

class ContainerAwareAction extends ActionDecorator
{

    /**
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * @param \Pascal\Router\ActionInterface    $action
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(
        ActionInterface $action,
        ContainerInterface $container
    ) {
        parent::__construct($action);

        $this->container = $container;
    }

    /**
     * @param \Pascal\Router\RouteArguments $routeArguments
     *
     * @return string
     */
    public function call(RouteArguments $routeArguments): string
    {
        $callable = $this->action->getCallable();

        $action = $this->action;

        if (is_array($callable)) {
            list($class, $method) = $callable;

            if (is_string($class) && class_exists($class) && method_exists($class, $method)) {
                $resolvedCallable = $this->container->get($class);

                $action = new Action($resolvedCallable);
            }
        }

        $action->call($routeArguments);
    }
}
