
# Leopard Events

Library for working with events in PSR-14 style for PHP 8.3+.

## Installation

```bash
composer require locky42/leopard-events
```

## Main Classes
- `EventManager` — static facade for registering and dispatching events
- `ListenerProvider` — stores listeners
- `EventDispatcher` — calls listeners for an event

## Usage Example

### Declaring an Event

```php
// Any simple class-object can be an event
class MyEvent {
    public function __construct(public ?object $obj = null) {}
    public $handled = false;
    public $updated = false;
}

class AnotherEvent {}
```

### Registering a Listener

```php
use Leopard\Events\EventManager;
use MyEvent;

// example
$user = Session::getUser();
EventManager::addEvent(new MyEvent($user), function ($event) {
    $event->obj->isNew = false;
});
```

### Dispatching an Event

```php
$event = EventManager::doEvent(MyEvent::class);
if ($event->handled) {
    // event was handled
}
```

or

```php
EventManager::doEvent(MyEvent::class);
```

### Passing an Object to an Event

```php
$obj = new \stdClass();
$obj->value = 10;
EventManager::addEvent(new MyEvent($obj), function ($event) {
    $event->obj->value = 20;
});
EventManager::doEvent(MyEvent::class);
// $obj->value == 20
```

### Adding a Listener as a Class Method

```php
EventManager::addEvent(new MyEvent(), [$this, 'updateObject']);
```

### Removing a Listener

```php
EventManager::removeEvent(new MyEvent(), [$this, 'updateObject']);
```

### Clearing All Listeners

```php
EventManager::getProvider()->clearListeners();
```

## Testing

Tests are located in `tests/` and use PHPUnit.

Run:
```bash
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```

## License
MIT
