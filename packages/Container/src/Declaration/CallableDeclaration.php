<?php

namespace Pascal\Container\Declaration;

use Pascal\Container\Container\AbstractContainer;

interface CallableDeclaration
{

    /**
     * @param AbstractContainer $container
     * @param string $method
     * @return mixed
     */
    public function call(AbstractContainer $container, string $method = '');
}
