<?php

namespace Pascal\Container\Declaration;

use Pascal\Container\Container\AbstractContainer;
use ReflectionClass;

abstract class Declaration
{

    /**
     * @var mixed
     */
    protected $statement;

    /**
     * Declaration constructor.
     * @param mixed $statement
     */
    public function __construct($statement)
    {
        $this->statement = $statement;
    }

    /**
     * @param AbstractContainer $container
     * @return mixed
     */
    abstract public function instantiate(AbstractContainer $container);

    /**
     * @param AbstractContainer $container
     * @param array $parameters
     * @return array
     */
    protected function resolveAbstracts(AbstractContainer $container, array $parameters): array
    {
        return array_map(function (\ReflectionParameter $parameter) use ($container) {
            $class = $parameter->getClass();

            $statement = ($class instanceof ReflectionClass && $container->has($class->getName()))
                ? $class->getName()
                : $parameter->getName();

            return $container->create($statement);
        }, $parameters);
    }
}
