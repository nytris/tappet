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

use InvalidArgumentException;
use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Automation\AutomationInterface;

/**
 * Class FieldActionRegistry.
 *
 * Maps field types to their action handlers and dispatches field actions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FieldActionRegistry implements FieldActionRegistryInterface
{
    /**
     * @var array<string, FieldActionHandlerInterface>
     */
    private $handlers = [];

    /**
     * @inheritDoc
     */
    public function handleFieldAction(
        string $fieldType,
        FieldActionInterface $action,
        AutomationInterface $automation
    ): void {
        if (!array_key_exists($fieldType, $this->handlers)) {
            throw new InvalidArgumentException(
                sprintf('No field action handler registered for field type "%s".', $fieldType)
            );
        }

        $actionHandlers = $this->handlers[$fieldType]->getHandlers();
        $actionClass = $action::class;

        if (!array_key_exists($actionClass, $actionHandlers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Field action handler for field type "%s" does not support action type "%s".',
                    $fieldType,
                    $actionClass
                )
            );
        }

        ($actionHandlers[$actionClass])($action, $automation);
    }

    /**
     * @inheritDoc
     */
    public function registerFieldActionHandler(string $fieldType, FieldActionHandlerInterface $handler): void
    {
        $this->handlers[$fieldType] = $handler;
    }
}
