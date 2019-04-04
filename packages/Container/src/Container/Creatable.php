<?php

namespace Pascal\Container\Container;

interface Creatable
{

    /**
     * @param string $abstract
     * @return mixed
     */
    public function create(string $abstract);
}
