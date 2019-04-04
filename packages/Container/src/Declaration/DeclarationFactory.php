<?php

namespace Pascal\Container\Declaration;

use Closure;
use Pascal\Container\Exception\CouldNotInstantiateException;
use Pascal\Container\Exception\UnknownTypeException;
use ReflectionClass;

final class DeclarationFactory
{

    /**
     * @param mixed $arg
     * @return Declaration
     * @throws \ReflectionException
     */
    public static function create($arg): Declaration
    {
        switch (gettype($arg)) {
            case 'boolean':
            case 'integer':
            case 'double':
            case 'string':
            case 'NULL':
                if (class_exists($arg)) {
                    $reflectionClass = new ReflectionClass($arg);

                    if (!$reflectionClass->isInstantiable()) {
                        throw new CouldNotInstantiateException;
                    }

                    return new ObjectDeclaration($arg);
                }
                return new StaticDeclaration($arg);
            case 'array':
                return new ArrayDeclaration($arg);
            case 'object':
                if ($arg instanceof Closure) {
                    return new ClosureDeclaration($arg);
                }
                return new ObjectDeclaration($arg);
            case 'resource':
                return new ResourceDeclaration($arg);
            case 'unknown type':
            default:
                throw new UnknownTypeException;
        }
    }
}
