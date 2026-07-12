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

namespace Tappet\Runner\Automation\Interaction;

use InvalidArgumentException;
use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Automation\AutomationInterface;

/**
 * Class InteractionRegistry.
 *
 * Maps interaction types to their handlers and dispatches interactions accordingly.
 *
 * @template TAutomation of AutomationInterface
 * @template-implements InteractionRegistryInterface<TAutomation>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class InteractionRegistry implements InteractionRegistryInterface
{
    /**
     * @var array<string, array<class-string<InteractionInterface>, callable(InteractionInterface, TAutomation): void>>
     */
    private array $handlers = [];

    /**
     * @inheritDoc
     */
    public function handleInteraction(
        string $interactionType,
        InteractionInterface $interaction,
        AutomationInterface $automation
    ): void {
        if (!array_key_exists($interactionType, $this->handlers)) {
            throw new InvalidArgumentException(
                sprintf('No interaction handler registered for interaction type "%s".', $interactionType)
            );
        }

        $interactionHandlers = $this->handlers[$interactionType];
        $interactionClass = $interaction::class;

        if (!array_key_exists($interactionClass, $interactionHandlers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Interaction handler for interaction type "%s" does not support interaction class "%s".',
                    $interactionType,
                    $interactionClass
                )
            );
        }

        ($interactionHandlers[$interactionClass])($interaction, $automation);
    }

    /**
     * @inheritDoc
     */
    public function registerInteractionHandler(string $interactionType, InteractionHandlerInterface $handler): void
    {
        $this->handlers[$interactionType] = array_merge($this->handlers[$interactionType] ?? [], $handler->getHandlers());
    }
}
