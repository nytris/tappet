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
 * Interface StateAssertionHandlerInterface.
 *
 * Handles state assertions for one or more StateAssertionInterface implementations.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface StateAssertionHandlerInterface
{
    /**
     * Returns a map of StateAssertionInterface FQCNs to callable handlers.
     *
     * Each key is the fully qualified class name of a StateAssertionInterface implementation,
     * and each value is a callable that accepts an instance of that class and performs
     * the corresponding state assertion.
     *
     * @return array<class-string<StateAssertionInterface>, callable(StateAssertionInterface): void>
     */
    public function getHandlers(): array;
}
