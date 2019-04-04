<?php

namespace Pascal\Router\Testing;

class InvokableClass
{

    public function __invoke()
    {
        return 'Calling from an invokable class.';
    }
}
