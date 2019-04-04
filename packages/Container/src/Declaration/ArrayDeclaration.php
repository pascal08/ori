<?php

namespace Pascal\Container\Declaration;

use Pascal\Container\Container\AbstractContainer;

class ArrayDeclaration extends Declaration
{

    /**
     * @param AbstractContainer $container
     * @return mixed
     */
    public function instantiate(AbstractContainer $container)
    {
        return array_map(function ($statement) use ($container) {
            $declaration = DeclarationFactory::create($statement);

            return $declaration->instantiate($container);
        }, $this->statement);
    }
}
