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

namespace Tappet\Core\Automation\Field;

use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Automation\AutomationInterface;

/**
 * Interface FieldActionRegistryInterface.
 *
 * Maps field types to their action handlers and dispatches field actions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface FieldActionRegistryInterface
{
    /**
     * Dispatches a field action to the handler registered for the given field type.
     */
    public function handleFieldAction(
        string $fieldType,
        FieldActionInterface $action,
        AutomationInterface $automation
    ): void;

    /**
     * Registers a handler for the given field type.
     */
    public function registerFieldActionHandler(string $fieldType, FieldActionHandlerInterface $handler): void;
}
