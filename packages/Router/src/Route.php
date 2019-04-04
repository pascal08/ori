<?php

namespace Pascal\Router;

class Route
{

    /**
     * @var string
     */
    private $route;

    /**
     * @param string $route
     */
    public function __construct(string $route)
    {
        $this->route = $route;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->route;
    }
}
