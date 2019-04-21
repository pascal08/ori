<?php

/*
 * In this example we are going to bind a primitive abstract to an
 * implementation. We will use a string as a primitive to act as an identifier
 * for this implementation. The implementation is just a simple Closure that
 * return an object instance.
 */

use Pascal\Container\Container\Container;

require_once __DIR__ . '/../../vendor/autoload.php';

// 1. Define the class we are going to instantiate in the Closure
class SomethingFancy {}

// 2. Instantiate the container
$container = new Container();

// 3. Bind the primitive (a string in this case) as an abstract to an implementation (a Closure in this case)
$container->bind('string-as-interface', function() {
    return new SomethingFancy();
});

// 4. Retrieve the implementation using the registered abstract
$instance = $container->get('string-as-interface');

echo get_class($instance); // Prints 'SomethingFancy'