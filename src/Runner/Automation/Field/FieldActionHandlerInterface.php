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

use Tappet\Runner\Action\FieldActionInterface;

/**
 * Interface FieldActionHandlerInterface.
 *
 * Handles field actions for a specific field type.
 *
 * @template TAction of FieldActionInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface FieldActionHandlerInterface
{
    /**
     * Returns a map of FieldActionInterface FQCNs to callable handlers.
     *
     * Each key is the fully qualified class name of a FieldActionInterface implementation,
     * and each value is a callable that accepts an instance of that class and performs
     * the corresponding field action.
     *
     * @return array<class-string<TAction>, callable(TAction): void>
     */
    public function getHandlers(): array;
}
