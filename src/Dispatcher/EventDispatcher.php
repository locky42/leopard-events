<?php

namespace Leopard\Events\Dispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * EventDispatcher is responsible for dispatching events to their respective listeners.
 * 
 * It implements the EventDispatcherInterface from PSR-14, allowing for standardized
 * event dispatching within the application.
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * Constructor.
     *
     * @param ListenerProvider $listenerProvider The listener provider.
     */
    public function __construct(protected ListenerProvider $provider) {}

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param object $event The event to dispatch.
     *
     * @return object The dispatched event.
     */
    public function dispatch(object $event): object
    {
        foreach ($this->provider->getListenersForEvent($event) as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            if ($event == new $event()) {
                $event = $listener['object'] ?? new $event();
            }

            $listener['listener']($event);
        }
        
        return $event;
    }
}
