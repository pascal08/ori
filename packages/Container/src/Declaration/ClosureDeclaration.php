<?php

namespace Pascal\Container\Declaration;

use Pascal\Container\Container\AbstractContainer;
use ReflectionFunction;

class ClosureDeclaration extends Declaration implements CallableDeclaration
{

    /**
     * @param AbstractContainer $container
     * @return mixed
     */
    public function instantiate(AbstractContainer $container)
    {
        return call_user_func($this->statement, $container);
    }

    /**
     * @param AbstractContainer $container
     * @param string $method
     * @return mixed
     * @throws \ReflectionException
     */
    public function call(AbstractContainer $container, string $method = '')
    {
        $reflectionFunction = new ReflectionFunction($this->statement);

        $methodArguments = $reflectionFunction->getParameters();

        $resolvedArguments = $this->resolveAbstracts($container, $methodArguments);

        return call_user_func_array($this->statement, $resolvedArguments);
    }
}
