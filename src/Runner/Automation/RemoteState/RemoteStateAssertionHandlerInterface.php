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
use Tappet\Runner\Automation\Matcher\MatcherRegistryInterface;

/**
 * Interface RemoteStateAssertionHandlerInterface.
 *
 * Handles remote state assertions for one or more RemoteStateAssertionInterface implementations.
 *
 * @template TAssertion of RemoteStateAssertionInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RemoteStateAssertionHandlerInterface
{
    /**
     * Returns a map of RemoteStateAssertionInterface FQCNs to callable handlers.
     *
     * Each key is the fully qualified class name of a {@see RemoteStateAssertionInterface} implementation,
     * and each value is a callable that accepts an instance of that class and performs
     * the corresponding remote state assertion. This typically involves fetching the remote value
     * via the API and dispatching it, along with the assertion's matcher, to the {@see MatcherRegistryInterface}.
     *
     * @return array<class-string<TAssertion>, callable(TAssertion): void>
     */
    public function getHandlers(): array;
}
