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

namespace Tappet\Core\Automation\Interaction;

use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Automation\AutomationInterface;

/**
 * Interface InteractionRegistryInterface.
 *
 * Maps interaction types to their handlers and dispatches interactions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface InteractionRegistryInterface
{
    /**
     * Dispatches the given interaction to the handler registered for the given interaction type.
     */
    public function handleInteraction(
        string $interactionType,
        InteractionInterface $interaction,
        AutomationInterface $automation
    ): void;

    /**
     * Registers a handler for the given interaction type.
     */
    public function registerInteractionHandler(string $interactionType, InteractionHandlerInterface $handler): void;
}
