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

use Tappet\Common\Exception\InvalidArgumentException;
use Tappet\Runner\Assertion\RemoteStateAssertionInterface;

/**
 * Class RemoteStateAssertionRegistry.
 *
 * Maps remote state assertion classes to their handlers and dispatches remote state
 * assertions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RemoteStateAssertionRegistry implements RemoteStateAssertionRegistryInterface
{
    /**
     * @var array<class-string<RemoteStateAssertionInterface>, callable(RemoteStateAssertionInterface): void>
     */
    private array $handlers = [];

    /**
     * @inheritDoc
     */
    public function handleRemoteStateAssertion(RemoteStateAssertionInterface $assertion): void
    {
        $assertionClass = $assertion::class;

        if (!array_key_exists($assertionClass, $this->handlers)) {
            throw new InvalidArgumentException(
                sprintf('No remote state assertion handler registered for assertion class "%s"', $assertionClass)
            );
        }

        ($this->handlers[$assertionClass])($assertion);
    }

    /**
     * @inheritDoc
     */
    public function registerRemoteStateAssertionHandler(RemoteStateAssertionHandlerInterface $handler): void
    {
        $this->handlers = array_merge($this->handlers, $handler->getHandlers());
    }
}
