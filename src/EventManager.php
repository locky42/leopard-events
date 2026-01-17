<?php

namespace Leopard\Events;

use Leopard\Events\Dispatcher\ListenerProvider;
use Leopard\Events\Dispatcher\EventDispatcher;

/**
 * EventManager is a static class that manages event dispatching and listener registration.
 * 
 * It provides methods to get the event dispatcher and listener provider instances,
 * as well as to add, remove, and execute events within the application.
 */
class EventManager
{
    /** @var EventDispatcher */
    protected static EventDispatcher $dispatcher;

    /** @var ListenerProvider */
    protected static ListenerProvider $provider;

    /**
     * Gets the event dispatcher instance.
     *
     * @return EventDispatcher The event dispatcher.
     */
    public static function getDispatcher(): EventDispatcher
    {
        if (!isset(self::$dispatcher)) {
            self::$provider = self::getProvider();
            self::$dispatcher = new EventDispatcher(self::$provider);
        }

        return self::$dispatcher;
    }

    /**
     * Gets the listener provider instance.
     *
     * @return ListenerProvider The listener provider.
     */
    public static function getProvider(): ListenerProvider
    {
        if (!isset(self::$provider)) {
            self::$provider = new ListenerProvider();
            self::$dispatcher = new EventDispatcher(self::$provider);
        }

        return self::$provider;
    }

    /**
     * Adds an event listener.
     * @param object|string $event    The event object or class name.
     * @param callable      $listener The listener callable.
     */
    public static function addEvent(object|string $event, callable $listener): void
    {
        if (is_string($event)) {
            $event = self::getProvider()->getListener($event)[0] ?? new $event();
            if (is_array($event)) {
                $event = $event['object'];
            }
        }

        self::getProvider()->addListener($event, $listener);
    }

    /**
     * Removes an event listener.
     * @param object   $event    The event object.
     * @param callable $listener The listener callable.
     */
    public static function removeEvent(object $event, callable $listener): void
    {
        self::getProvider()->removeListener($event, $listener);
    }

    /**
     * Executes an event.
     *
     * @param string $event The event class name.
     * @param mixed  ...$args The arguments to pass to the event constructor.
     *
     * @return object The dispatched event.
     */
    public static function doEvent(string $event, ...$args): object
    {
        if (is_array($args) && count($args)) {
            return self::getDispatcher()->dispatch(new $event(...$args));
        }
        return self::getDispatcher()->dispatch(new $event());
    }
}
