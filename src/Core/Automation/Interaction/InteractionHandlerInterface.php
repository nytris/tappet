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

/**
 * Interface InteractionHandlerInterface.
 *
 * Handles interactions for one or more InteractionInterface implementations.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface InteractionHandlerInterface
{
    /**
     * Returns a map of InteractionInterface FQCNs to callable handlers.
     *
     * Each key is the fully qualified class name of an InteractionInterface implementation,
     * and each value is a callable that accepts an instance of that class and performs
     * the corresponding interaction.
     *
     * @return array<class-string<InteractionInterface>, callable(InteractionInterface): void>
     */
    public function getHandlers(): array;
}
