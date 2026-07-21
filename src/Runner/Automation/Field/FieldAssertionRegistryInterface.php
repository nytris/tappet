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
 * Interface FieldAssertionRegistryInterface.
 *
 * Maps field types to their assertion handlers and dispatches field assertions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface FieldAssertionRegistryInterface
{
    /**
     * Dispatches a field assertion to the handler registered for the given field type.
     */
    public function handleFieldAssertion(string $fieldType, FieldAssertionInterface $assertion): void;

    /**
     * Registers a handler for the given field type.
     *
     * @param FieldAssertionHandlerInterface<FieldAssertionInterface> $handler
     */
    public function registerFieldAssertionHandler(string $fieldType, FieldAssertionHandlerInterface $handler): void;
}
