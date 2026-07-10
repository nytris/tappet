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
 * Interface EventListenerRegistryInterface.
 *
 * Registers event listeners.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface EventListenerRegistryInterface
{
    /**
     * Registers the given listener for the given event class.
     *
     * @template TEvent of EventInterface
     * @param class-string<TEvent> $eventClass
     * @param callable(TEvent): void $listener
     */
    public function addEventListener(string $eventClass, callable $listener): void;

    /**
     * Removes the given listener for the given event class.
     *
     * @template TEvent of EventInterface
     * @param class-string<TEvent> $eventClass
     * @param callable(TEvent): void $listener
     */
    public function removeEventListener(string $eventClass, callable $listener): void;
}
