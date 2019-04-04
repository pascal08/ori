<?php

namespace Pascal\Router;

class RouteArguments
{

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->arguments;
    }
}
