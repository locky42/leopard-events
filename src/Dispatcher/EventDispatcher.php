<?php

namespace Leopard\Events\Dispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

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

            $listener['listener']($listener['object'] ?? $event);
        }
        
        return $listener['object'] ?? $event;
    }
}
