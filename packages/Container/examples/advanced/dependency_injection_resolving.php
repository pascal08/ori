<?php

/*
 * This example will demonstrate how the Container can be used to resolve
 * dependencies on runtime using argument typehints.
 */

use Pascal\Container\Container\Container;

require_once __DIR__ . '/../../vendor/autoload.php';

// 1. Define the interface that will act as the abstract
interface SomeInterface {}

// 2. Define the class that will act as the implementation
class SomeImplementation implements SomeInterface {}

// 3. Define a class that will act as a web controller from where we are going
//    to resolve its dependencies.
class WebController
{

    public static function home(SomeInterface $someInterface)
    {
        echo get_class($someInterface);
    }
}

// 4. Instantiate the container
$container = new Container();

// 5. Bind the interface to the implementation
$container->bind(SomeInterface::class, function() {
    return new SomeImplementation();
});

// 6. Call the WebController and let it resolve its dependencies
$container->call(WebController::class, 'home');
