<?php

namespace Leopard\Events;

use Leopard\Events\Dispatcher\ListenerProvider;
use Leopard\Events\Dispatcher\EventDispatcher;

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
     * @param object   $event    The event object.
     * @param callable $listener The listener callable.
     */
    public static function addEvent(object $event, callable $listener): void
    {
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
     * Dispatches an event.
     * @param string $event The event class name.
     * @return object The event object.
     */
    public static function doEvent(string $event): object
    {
        return self::getDispatcher()->dispatch(new $event());
    }
}
