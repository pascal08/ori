<?php

namespace Pascal\Container\Declaration;

use Pascal\Container\Container\AbstractContainer;
use Pascal\Container\Exception\MethodDoesNotExist;
use Pascal\Container\Exception\NotCallableException;
use ReflectionClass;
use ReflectionMethod;

class ObjectDeclaration extends Declaration implements CallableDeclaration
{

    /**
     * @param AbstractContainer $container
     * @return mixed
     * @throws \ReflectionException
     */
    public function instantiate(AbstractContainer $container)
    {
        $objectReflection = new ReflectionClass($this->statement);

        $constructor = $objectReflection->getConstructor();

        if ($constructor instanceof ReflectionMethod) {
            return $this->resolveObjectWithConstructor($container, $constructor, $this->statement);
        }

        return new $this->statement;
    }

    /**
     * @param AbstractContainer $container
     * @param ReflectionMethod $constructor
     * @param string $concrete
     * @return object
     * @throws \ReflectionException
     */
    private function resolveObjectWithConstructor(
        AbstractContainer $container,
        ReflectionMethod $constructor,
        string $concrete
    ) {
        $parameters = $constructor->getParameters();

        $resolvedParameters = $this->resolveAbstracts($container, $parameters);

        return (new ReflectionClass($concrete))->newInstanceArgs($resolvedParameters);
    }

    /**
     * @param AbstractContainer $container
     * @param string $method
     * @return mixed
     * @throws \ReflectionException
     */
    public function call(AbstractContainer $container, string $method = '')
    {
        $reflectionClass = new ReflectionClass($this->statement);

        if (!$reflectionClass->hasMethod($method)) {
            throw new MethodDoesNotExist;
        }

        $instance = $this->instantiate($container);

        $toCall = [$instance, $method];

        if (!is_callable($toCall)) {
            throw new NotCallableException;
        }

        $methodArguments = $reflectionClass->getMethod($method)->getParameters();

        $resolvedArguments = $this->resolveAbstracts($container, $methodArguments);

        return call_user_func_array($toCall, $resolvedArguments);
    }
}
