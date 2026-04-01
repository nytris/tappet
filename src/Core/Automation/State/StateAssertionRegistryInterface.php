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

namespace Tappet\Core\Automation\State;

use Tappet\Core\Assertion\StateAssertionInterface;

/**
 * Interface StateAssertionRegistryInterface.
 *
 * Maps state assertion types to their handlers and dispatches state assertions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface StateAssertionRegistryInterface
{
    /**
     * Dispatches the given state assertion to the handler registered for the given state type.
     */
    public function handleStateAssertion(string $stateType, StateAssertionInterface $assertion): void;

    /**
     * Registers a handler for the given state type.
     */
    public function registerStateAssertionHandler(string $stateType, StateAssertionHandlerInterface $handler): void;
}
