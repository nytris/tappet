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
 * Interface EventDispatcherInterface.
 *
 * Dispatches events.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface EventDispatcherInterface extends EventListenerRegistryInterface
{
    /**
     * Dispatches the given event.
     */
    public function dispatch(EventInterface $event): void;
}
