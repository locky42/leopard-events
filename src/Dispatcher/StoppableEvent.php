<?php

namespace Leopard\Events\Dispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Base class for stoppable events.
 * @extends StoppableEventInterface
 */
class StoppableEvent implements StoppableEventInterface
{
    /**
     * @var bool Indicates whether propagation is stopped.
     */
    protected bool $propagationStopped = false;

    /**
     * Stops the propagation of the event to further listeners.
     *
     * @return void
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    /**
     * Checks if the propagation of the event has been stopped.
     *
     * @return bool True if propagation is stopped, false otherwise.
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }
}
