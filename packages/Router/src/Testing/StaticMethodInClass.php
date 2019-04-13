<?php

namespace Pascal\Router\Testing;

class StaticMethodInClass
{

    public static function home(): string
    {
        return 'Calling from a static class method.';
    }
}
