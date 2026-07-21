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

use InvalidArgumentException;
use Tappet\Runner\Assertion\FieldAssertionInterface;

/**
 * Class FieldAssertionRegistry.
 *
 * Maps field types to their assertion handlers and dispatches field assertions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FieldAssertionRegistry implements FieldAssertionRegistryInterface
{
    /**
     * @var array<string, array<class-string<FieldAssertionInterface>, callable(FieldAssertionInterface): void>>
     */
    private array $handlers = [];

    /**
     * @inheritDoc
     */
    public function handleFieldAssertion(string $fieldType, FieldAssertionInterface $assertion): void
    {
        if (!array_key_exists($fieldType, $this->handlers)) {
            throw new InvalidArgumentException(
                sprintf('No field assertion handler registered for field type "%s"', $fieldType)
            );
        }

        $assertionHandlers = $this->handlers[$fieldType];
        $assertionClass = $assertion::class;

        if (!array_key_exists($assertionClass, $assertionHandlers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Field assertion handler for field type "%s" does not support assertion type "%s"',
                    $fieldType,
                    $assertionClass
                )
            );
        }

        ($assertionHandlers[$assertionClass])($assertion);
    }

    /**
     * @inheritDoc
     */
    public function registerFieldAssertionHandler(string $fieldType, FieldAssertionHandlerInterface $handler): void
    {
        $this->handlers[$fieldType] = array_merge($this->handlers[$fieldType] ?? [], $handler->getHandlers());
    }
}
