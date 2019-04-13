<?php

namespace Pascal\Container\Container;

use InvalidArgumentException;
use Pascal\Container\Declaration\CallableDeclaration;
use Pascal\Container\Declaration\DeclarationFactory;
use Pascal\Container\Declaration\ObjectDeclaration;
use Pascal\Container\Exception\BindingNotFoundException;
use Pascal\Container\Exception\CouldNotInstantiateException;
use Pascal\Container\Exception\NotCallableException;

class Container extends AbstractContainer
{

    /**
     * @var array
     */
    protected $binds;

    /**
     * @var mixed[]
     */
    protected $instances;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->binds = [];
        $this->instances = [];
    }

    /**
     * @param string $abstract
     * @return mixed
     * @throws BindingNotFoundException
     * @throws \ReflectionException
     */
    public function get($abstract)
    {
        $this->guardAgainstNonStringAbstracts($abstract);

        if (!$this->hasBeenResolved($abstract)) {
            $this->create($abstract);
        }

        return $this->instances[$abstract];
    }

    /**
     * @param string $abstract
     * @return bool
     */
    public function has($abstract)
    {
        $this->guardAgainstNonStringAbstracts($abstract);

        return array_key_exists($abstract, $this->binds);
    }

    /**
     * @param string $abstract
     * @param mixed $concrete
     */
    public function bind(string $abstract, $concrete): void
    {
        $this->binds[$abstract] = $concrete;
    }

    /**
     * @param string $abstract
     * @return mixed|null
     * @throws BindingNotFoundException
     * @throws CouldNotInstantiateException
     * @throws \ReflectionException
     */
    public function create(string $abstract)
    {
        $concrete = $this->getConcreteFromAbstractOrFail($abstract);

        $declaration = DeclarationFactory::create($concrete);

        return $this->instances[$abstract] = $declaration->instantiate($this);
    }

    /**
     * @param mixed $callable
     * @param string $method
     * @return mixed
     * @throws \ReflectionException
     */
    public function call($callable, string $method)
    {
        $declaration = DeclarationFactory::create($callable);

        if (!($declaration instanceof CallableDeclaration)) {
            throw new NotCallableException;
        }

        return $declaration->call($this, $method);
    }

    /**
     * @param string $abstract
     * @return bool
     */
    private function hasBeenResolved(string $abstract)
    {
        return array_key_exists($abstract, $this->instances);
    }

    /**
     * @param string $abstract
     * @return mixed
     * @throws BindingNotFoundException
     */
    private function getConcreteFromAbstractOrFail(string $abstract)
    {
        try {
            if ((new \ReflectionClass($abstract))->isInstantiable()) {
                return $abstract;
            }
        } catch (\ReflectionException $exception) {
            //
        }

        if (!$this->has($abstract)) {
            throw new BindingNotFoundException;
        }

        return $this->binds[$abstract];
    }

    /**
     * @param mixed $abstract
     */
    private function guardAgainstNonStringAbstracts($abstract): void
    {
        if (!is_string($abstract)) {
            throw new InvalidArgumentException(sprintf(
                'The argument $abstract must be of type string, %s given',
                is_object($abstract) ? get_class($abstract) : gettype($abstract)
            ));
        }
    }
}
