<?php

namespace Leopard\Events\Dispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Listener provider implementation.
 */
class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @var array<string, array<int, callable>>
     */
    protected array $listeners = [];

    /**
     * Adds a listener for a specific event.
     *
     * @param object   $event    The event object.
     * @param callable $listener The listener callable.
     *
     * @return void
     */
    public function addListener(object $event, callable $listener): void
    {
        $this->listeners[get_class($event)][] = [
            'object' => $event,
            'listener' => $listener
        ];
    }

    /**
     * Removes a listener for a specific event.
     * @param object   $event    The event object.
     * @param callable $listener The listener callable.
     * @return void
     */
    public function removeListener(object $event, callable $listener): void
    {
        $eventName = get_class($event);
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $key => $registeredListener) {
                if ($registeredListener['listener'] === $listener) {
                    unset($this->listeners[$eventName][$key]);
                }
            }
            // Reindex the array to maintain consistent keys
            $this->listeners[$eventName] = array_values($this->listeners[$eventName]);
        }
    }

    /**
     * Clears all registered listeners.
     *
     * @return void
     */
    public function clearListeners(): void
    {
        $this->listeners = [];
    }

    /**
     * Retrieves the listeners for a given event name.
     *
     * @param string $eventName The event name.
     *
     * @return array<int, callable>|null The listeners for the event name or null if none exist.
     */
    public function getListener(string $eventName): ?array
    {
        return $this->listeners[$eventName] ?? null;
    }

    /**
     * Retrieves all registered listeners.
     *
     * @return array<string, array<int, callable>> The registered listeners.
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    /**
     * Retrieves the listeners for a given event.
     *
     * @param object $event The event object.
     *
     * @return iterable<callable> The listeners for the event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventName = get_class($event);
        if (isset($this->listeners[$eventName])) {
            foreach ($this->listeners[$eventName] as $registeredListener) {
                yield $registeredListener;
            }
        }
    }
}
