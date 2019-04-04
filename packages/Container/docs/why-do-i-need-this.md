# Why do I need this?

## Dependency injection

> Dependency injection? What is that?

Suppose you are given some kind of machine. Every time you put a coin into the machine it dispenses a lollypop.

You notice the machine does something odd. Every time you retrieve an item from the machine it constructs a lever and set it in the upward position. The lever has a label on it that says: "If this lever is in the upward position this machine dispenses a lollypop. If this lever is in the downward position this machine dispenses a bouncing ball". You would like to be in control of this lever, but the machine is constructed in a way you can't reach this lever.

You decide to open the machine and pull out the lever-constructing component. You modify the machine so that the lever can now be connected to the outside of the machine. This way you can decide what the position of the lever should be before putting a coin into the machine.

> Okay, but why did I read this?

Pulling out the mechanism inside the machine that creates a lever you can't control and instead place the lever on the outside of your machine allows you to regain control over the input. This is quite similar to dependency injection.

> I still don't get it. Give me an example.

Suppose you have these classes:

```php
class Machine 
{
    public function retrieveItem(Coin $coin)
    {
        $lever1 = new $lever1;
        
        if($lever1->isUp()) {
            return new Lollypop;
        } else {
            return new BouncingBall;
        }
    }
}

class Lever 
{
    const UP = 'up';
    const DOWN = 'down';
    
    public $position = self::UP;
    
    public function flip()
    {
        if($this->isUp()) {
            $this->position = self::DOWN;
        } else {
            $this->position = self::UP;
        }
    }
    
    public function isUp() 
    {
        return $this->position === self::UP;
    }
}

class Coin {}
class Lollypop {}
class BouncingBall {}
```

When we retrieve an item from the machine:

```php
$machine = new Machine;
$item = $machine->retrieveItem(new Coin); // $item is always of type Lollypop.
```

The output of the machine now depends on something that is not within your control. To fix this "pull out" the lever creating component and inject it as a dependency of the machine through its input arguments. Like this:

```php
class Machine 
{
    public function retrieveItem(Coin $coin, Lever $lever)
    {
        if($lever1->isUp()) {
            return new Lollypop;
        } else {
            return new BouncingBall;
        }
    }
}
```

We can now feed `lever` in the desired position as input arguments.

```php
$machine = new Machine;

$lever = new Lever;
$lever->position = Lever::UP;

$item = $machine->retrieveItem(new Coin, $lever); // $item is of type Lollypop

$lever->position = Lever::DOWN;
$item = $machine->retrieveItem(new Coin, $lever); // $item is now of type BouncingBall
```

You now have successfully applied dependency injection. Pascal\Container will help you managing all those dependencies. In this example it could create a lever for you anytime you want to retrieve an item from the machine. 


```php
$container = new \Pascal\Container\Container;

$container->bind(Lever::class, function() {
    return new Lever;
});

$container->call(Machine::class, 'retrieveItem');
```
