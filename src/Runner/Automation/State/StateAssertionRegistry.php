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

namespace Tappet\Runner\Automation\State;

use InvalidArgumentException;
use Tappet\Runner\Assertion\StateAssertionInterface;

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
     * @var array<string, array<class-string<StateAssertionInterface>, callable(StateAssertionInterface): void>>
     */
    private array $handlers = [];

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

        $assertionHandlers = $this->handlers[$stateType];
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
        $this->handlers[$stateType] = array_merge($this->handlers[$stateType] ?? [], $handler->getHandlers());
    }
}
