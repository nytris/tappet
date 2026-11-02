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

namespace Tappet\Runner\Automation\RemoteState;

use Tappet\Runner\Assertion\RemoteStateAssertionInterface;
use Tappet\Runner\Automation\State\StateAssertionRegistryInterface;

/**
 * Interface RemoteStateAssertionRegistryInterface.
 *
 * Maps remote state assertion classes to their handlers and dispatches remote state
 * assertions accordingly.
 *
 * Unlike e.g. {@see StateAssertionRegistryInterface}, a remote state assertion's handler is never
 * sniffed from the DOM (there is nothing on the page to sniff) - handlers are dispatched
 * purely by the assertion's own FQCN, entirely custom per consuming application.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RemoteStateAssertionRegistryInterface
{
    /**
     * Dispatches the given remote state assertion to its registered handler.
     */
    public function handleRemoteStateAssertion(RemoteStateAssertionInterface $assertion): void;

    /**
     * Registers a handler for one or more remote state assertion classes.
     *
     * @param RemoteStateAssertionHandlerInterface<RemoteStateAssertionInterface> $handler
     */
    public function registerRemoteStateAssertionHandler(RemoteStateAssertionHandlerInterface $handler): void;
}
