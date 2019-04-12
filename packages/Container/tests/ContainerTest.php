<?php

namespace Pascal\Container\Tests;

use Pascal\Container\Exception\BindingNotFoundException;
use Pascal\Container\Container\Container;
use Pascal\Container\Exception\CouldNotInstantiateException;
use Pascal\Container\Exception\MethodDoesNotExist;
use Pascal\Container\Exception\NotCallableException;
use Pascal\Container\Exception\UnknownTypeException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{

    /** @test */
    public function it_should_create_new_instances_from_a_closure_declaration()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, function () {
            return new DummyObject;
        });

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(DummyObject::class, get_class($instance));
    }

    /** @test */
    public function it_should_create_new_instances_from_a_fully_qualified_class_name_declaration()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, DummyObject::class);

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(DummyObject::class, get_class($instance));
    }

    /** @test */
    public function it_should_create_new_instances_from_a_instantiated_class()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, new DummyObject);

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(DummyObject::class, get_class($instance));
    }

    /** @test */
    public function it_should_create_new_instances_from_an_array_of_declarations()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, [DummyObject::class, function() {
            return new AnotherDummyObject;
        }]);

        $instances = $container->create(DummyInterface::class);

        self::assertEquals(2, count($instances));

        self::assertEquals(DummyObject::class, get_class($instances[0]));

        self::assertEquals(AnotherDummyObject::class, get_class($instances[1]));
    }

    /** @test */
    public function it_should_create_new_instances_from_a_boolean_declaration()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, true);

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(true, $instance);
    }

    /** @test */
    public function it_should_create_new_instances_from_a_integer_declaration()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, 42);

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(42, $instance);
    }

    /** @test */
    public function it_should_create_new_instances_from_a_double_declaration()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, 12.34);

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(12.34, $instance);
    }

    /** @test */
    public function it_should_create_new_instances_from_a_string_declaration()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, 'someString');

        $instance = $container->create(DummyInterface::class);

        self::assertEquals('someString', $instance);
    }

    /** @test */
    public function it_should_create_new_instances_from_a_null_declaration()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, null);

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(null, $instance);
    }

    /** @test */
    public function it_should_create_new_instances_from_a_resource()
    {
        $container = new Container;

        $file = fopen(__DIR__ . '/somefile.txt','r');

        $container->bind(DummyInterface::class, $file);

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(true, is_resource($instance));
    }

    /** @test */
    public function it_should_throw_an_exception_when_creating_a_new_instance_from_a_unknown_type()
    {
        self::expectException(UnknownTypeException::class);

        $container = new Container;

        $file = fopen(__DIR__ . '/somefile.txt','r');
        fclose($file);

        $container->bind(DummyInterface::class, $file);

        $container->create(DummyInterface::class);
    }

    /** @test */
    public function it_should_create_new_instances_every_time()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, DummyObject::class);

        $instance1 = $container->create(DummyInterface::class);

        $instance2 = $container->create(DummyInterface::class);

        self::assertEquals(DummyObject::class, get_class($instance1));

        self::assertEquals(DummyObject::class, get_class($instance2));

        self::assertNotSame($instance1, $instance2);
    }

    /** @test */
    public function it_should_create_new_instances_every_time_for_object_with_dependencies()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, DummyObject::class);

        $container->bind(DummyWithDependenciesInterface::class, DummyObjectWithDependencies::class);

        $instance1 = $container->create(DummyWithDependenciesInterface::class);

        $instance2 = $container->create(DummyWithDependenciesInterface::class);

        self::assertEquals(DummyObjectWithDependencies::class, get_class($instance1));

        self::assertEquals(DummyObjectWithDependencies::class, get_class($instance2));

        self::assertNotSame($instance1, $instance2);

        self::assertNotSame($instance1->dependency, $instance2->dependency);
    }

    /** @test */
    public function it_should_get_the_same_instance_once_it_has_been_resolved()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, DummyObject::class);

        $instance1 = $container->get(DummyInterface::class);

        $instance2 = $container->get(DummyInterface::class);

        self::assertEquals(DummyObject::class, get_class($instance1));

        self::assertEquals(DummyObject::class, get_class($instance2));

        self::assertSame($instance1, $instance2);
    }

    /** @test */
    public function it_should_create_new_instances_from_a_non_bound_concrete_class()
    {
        $container = new Container;

        $instance = $container->create(DummyObject::class);

        self::assertEquals(DummyObject::class, get_class($instance));
    }

    /** @test */
    public function it_should_create_new_instances_from_a_non_bound_concrete_class_with_dependencies()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, DummyObject::class);

        $instance = $container->create(DummyObjectWithDependencies::class);

        self::assertEquals(DummyObjectWithDependencies::class, get_class($instance));

        self::assertEquals(DummyObject::class, get_class($instance->dependency));
    }

    /** @test */
    public function it_should_throw_an_exception_for_new_instances_of_a_non_registered_binding()
    {
        self::expectException(BindingNotFoundException::class);

        $container = new Container;

        $container->create(DummyInterface::class);
    }

    /** @test */
    public function it_should_throw_an_exception_for_new_instances_of_a_non_instantiatable_abstract_binding()
    {
        self::expectException(CouldNotInstantiateException::class);

        $container = new Container;

        $container->bind(DummyInterface::class, AbstractDummyObject::class);

        $container->create(DummyInterface::class);
    }

    /** @test */
    public function it_should_return_true_given_an_abstract_that_is_registered_as_a_binding()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, function () {
            return new DummyObject;
        });

        $hasInstance = $container->has(DummyInterface::class);

        self::assertEquals(true, $hasInstance);
    }

    /** @test */
    public function it_should_return_false_given_an_abstract_that_is_not_registered_as_a_binding()
    {
        $container = new Container;

        $hasInstance = $container->has(DummyInterface::class);

        self::assertEquals(false, $hasInstance);
    }

    /** @test */
    public function it_should_create_new_instances_with_dependencies_using_anonymous_factories()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, function () {
            return new DummyObject;
        });

        $container->bind(DummyWithDependenciesInterface::class, function ($container) {
            return new DummyObjectWithDependencies($container->create(DummyInterface::class));
        });

        $instance = $container->create(DummyWithDependenciesInterface::class);

        self::assertEquals(DummyObjectWithDependencies::class, get_class($instance));

        self::assertEquals(DummyObject::class, get_class($instance->dependency));
    }

    /** @test */
    public function it_should_create_new_instances_with_dependencies_using_fully_qualified_class_names()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, DummyObject::class);

        $container->bind(DummyWithDependenciesInterface::class, DummyObjectWithDependencies::class);

        $instance = $container->create(DummyWithDependenciesInterface::class);

        self::assertEquals(DummyObjectWithDependencies::class, get_class($instance));

        self::assertEquals(DummyObject::class, get_class($instance->dependency));
    }

    /** @test */
    public function it_should_bind_scalars_by_variable_name_to_create_new_instances_of_objects_with_scalar_constructor_arguments()
    {
        $container = new Container;

        $container->bind(DummyInterface::class, DummyObjectWithScalarConstructorArguments::class);

        $container->bind( 'name', 'Jack Sparrow');

        $instance = $container->create(DummyInterface::class);

        self::assertEquals(DummyObjectWithScalarConstructorArguments::class, get_class($instance));

        self::assertEquals('Jack Sparrow', $instance->name);
    }

    /** @test */
    public function it_should_call_a_method_on_an_instance_and_resolve_it_abstract_arguments()
    {
        $container = new Container();

        $container->bind(DummyInterface::class, DummyObject::class);

        $container->bind(DummyObjectWithMethods::class, function() {
            return new DummyObjectWithMethods;
        });

        $result = $container->call(DummyObjectWithMethods::class, 'someMethodWithAbstractArguments');

        self::assertEquals(DummyObject::class, get_class($result));
    }

    /** @test */
    public function it_should_call_a_method_on_an_instance_and_resolve_it_scalar_arguments()
    {
        $container = new Container();

        $container->bind(DummyInterface::class, DummyObject::class);

        $container->bind(DummyObjectWithMethods::class, function() {
            return new DummyObjectWithMethods;
        });

        $container->bind( 'name', 'Jack Sparrow');

        $result = $container->call(DummyObjectWithMethods::class, 'someMethodWithScalarArguments');

        self::assertEquals('Jack Sparrow', $result);
    }

    /** @test */
    public function it_should_throw_an_exception_when_calling_a_method_on_an_instance_that_does_not_exist()
    {
        self::expectException(MethodDoesNotExist::class);

        $container = new Container();

        $container->bind(DummyObjectWithMethods::class, function() {
            return new DummyObjectWithMethods;
        });

        $result = $container->call(DummyObjectWithMethods::class, 'someUnexistingMethod');

        self::assertEquals(DummyObject::class, get_class($result));
    }

    /** @test */
    public function it_should_throw_an_exception_when_calling_a_method_on_an_instance_that_is_not_visible()
    {
        self::expectException(NotCallableException::class);

        $container = new Container();

        $container->bind(DummyObjectWithMethods::class, function() {
            return new DummyObjectWithMethods;
        });

        $container->call(DummyObjectWithMethods::class, 'someNonVisibleMethod');
    }

    /** @test */
    public function it_should_call_an_anonymous_function_and_resolve_it_arguments()
    {
        $container = new Container();

        $container->bind(DummyInterface::class, DummyObject::class);

        $container->bind(DummyObjectWithMethod::class, function() {
            return new DummyObjectWithMethod;
        });

        $result = $container->call(function(DummyInterface $dummy) {
            return $dummy;
        }, 'someMethodCall');

        self::assertEquals(DummyObject::class, get_class($result));
    }

    /** @test */
    public function it_should_throw_an_exception_when_a_non_callable_is_being_called()
    {
        self::expectException(NotCallableException::class);

        $container = new Container();

        $container->call(2, 'someMethodCall');
    }

    /** @test */
    public function it_should_throw_an_exception_for_getting_instances_by_a_non_string_abstract()
    {
        self::expectException(\InvalidArgumentException::class);

        $container = new Container;

        $container->get(function () {
            return null;
        });
    }

    /** @test */
    public function it_should_throw_an_exception_for_asking_about_bound_instances_by_a_non_string_abstract()
    {
        self::expectException(\InvalidArgumentException::class);

        $container = new Container;

        $container->has(2);
    }
}

interface DummyInterface
{
    //
}

class DummyObject implements DummyInterface
{
    //
}

class AnotherDummyObject implements DummyInterface
{
    //
}

abstract class AbstractDummyObject implements DummyInterface
{
    //
}

interface DummyWithDependenciesInterface
{
    //
}

class DummyObjectWithDependencies implements DummyWithDependenciesInterface
{
    public $dependency;

    public function __construct(DummyInterface $dependency)
    {
        $this->dependency = $dependency;
    }
}

class DummyObjectWithScalarConstructorArguments
{
    public $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

class DummyObjectWithMethods
{

    public function someMethodWithAbstractArguments(DummyInterface $dummy)
    {
        return $dummy;
    }

    protected function someNonVisibleMethod()
    {
        //
    }

    public function someMethodWithScalarArguments(string $name)
    {
        return $name;
    }
}