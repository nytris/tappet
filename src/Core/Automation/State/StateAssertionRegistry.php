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

use InvalidArgumentException;
use Tappet\Core\Assertion\StateAssertionInterface;

/**
 * Class StateAssertionRegistry.
 *
 * Maps state assertion types to their handlers and dispatches state assertions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class StateAssertionRegistry implements StateAssertionRegistryInterface
{
    /**
     * @var array<string, StateAssertionHandlerInterface>
     */
    private $handlers = [];

    /**
     * @inheritDoc
     */
    public function handleStateAssertion(string $stateType, StateAssertionInterface $assertion): void
    {
        if (!array_key_exists($stateType, $this->handlers)) {
            throw new InvalidArgumentException(
                sprintf('No state assertion handler registered for state type "%s".', $stateType)
            );
        }

        $assertionHandlers = $this->handlers[$stateType]->getHandlers();
        $assertionClass = $assertion::class;

        if (!array_key_exists($assertionClass, $assertionHandlers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'State assertion handler for state type "%s" does not support assertion type "%s".',
                    $stateType,
                    $assertionClass
                )
            );
        }

        ($assertionHandlers[$assertionClass])($assertion);
    }

    /**
     * @inheritDoc
     */
    public function registerStateAssertionHandler(string $stateType, StateAssertionHandlerInterface $handler): void
    {
        $this->handlers[$stateType] = $handler;
    }
}
