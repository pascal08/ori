<?php

namespace Pascal\Router\Testing;

class InvokableClass
{

    public function __invoke(): string
    {
        return 'Calling from an invokable class.';
    }
}
