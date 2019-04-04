# Getting started with Pascal/Container

## Prerequisites

 - PHP 7.1 or higher

## Installation

Install Pascal/Container using Composer:

```bash
composer require pascal08/container
```

## Start using it!

The container is used in three simple steps:

1. Setup the container

```php
$container = new \Pascal\Container\Container;
```

2. Bind an abstract to an implementation:

```php
$container->bind(SomeInterface::class, SomeClass::class);
```

3. Let the container resolve your abstractions:

```php
$instance = $container->make(SomeInterface::class);
```

The container will automatically resolve the class `SomeClass` that was bound to `SomeInterface`!