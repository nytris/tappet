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

namespace Tappet\Runner\Automation\Field;

use Tappet\Runner\Assertion\FieldAssertionInterface;

/**
 * Interface FieldAssertionHandlerInterface.
 *
 * Handles field assertions for one or more FieldAssertionInterface implementations.
 *
 * @template TAssertion of FieldAssertionInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface FieldAssertionHandlerInterface
{
    /**
     * Returns a map of FieldAssertionInterface FQCNs to callable handlers.
     *
     * Each key is the fully qualified class name of a FieldAssertionInterface implementation,
     * and each value is a callable that accepts an instance of that class and performs
     * the corresponding field assertion.
     *
     * @return array<class-string<TAssertion>, callable(TAssertion): void>
     */
    public function getHandlers(): array;
}
