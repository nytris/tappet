<?php

/*
 * Tappet - Enjoyable GUI testing
 * Copyright (c) Dan Phillimore (asmblah)
 * https://github.com/nytris/tappet/
 *
 * Released under the MIT license.
 * https://github.com/nytris/tappet/raw/main/MIT-LICENSE.txt
 */

declare(strict_types=1);

namespace Tappet\Common\Event;

/**
 * Class EventDispatcher.
 *
 * Dispatches events.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<class-string<EventInterface>, (callable(EventInterface): void)[]>
     */
    private array $listeners = [];

    /**
     * @inheritDoc
     */
    public function addEventListener(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(EventInterface $event): void
    {
        foreach ($this->listeners[$event::class] ?? [] as $listener) {
            $listener($event);
        }
    }

    /**
     * @inheritDoc
     */
    public function removeEventListener(string $eventClass, callable $listener): void
    {
        foreach ($this->listeners[$eventClass] ?? [] as $key => $existingListener) {
            if ($existingListener === $listener) {
                unset($this->listeners[$eventClass][$key]);

                return;
            }
        }
    }
}
