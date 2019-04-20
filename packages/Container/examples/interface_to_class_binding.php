<?php

use Pascal\Container\Container\Container;

require_once __DIR__ . '/../vendor/autoload.php';

/*
 * In this example we are going to bind a PHP interface to a implementation.
 * This is the most common scenario when using the Container. It allows you to
 * swap an implementation of an interface through your whole application by
 * just changing it at one place: the Container's configuration.
 */

// 1. Define the interface that will at as the abstract
interface SomeInterface {}

// 2. Define the class that will act as the implementation
class SomeImplementation implements SomeInterface {}

// 3. Instantiate the container
$container = new Container();

// 4. Bind the interface to the implementation
$container->bind(SomeInterface::class, function() {
    return new SomeImplementation();
});

// 4. Retrieve the implementation using the registered abstract
$instance = $container->get(SomeInterface::class);

echo get_class($instance); // Prints 'SomeImplementation'