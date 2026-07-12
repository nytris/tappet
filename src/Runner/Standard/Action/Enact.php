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

namespace Tappet\Runner\Standard\Action;

use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class Enact.
 *
 * Performs an interaction with the page, such as clicking a button.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Enact implements InteractionInterface
{
    public function __construct(
        private readonly string $interactionHandle
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getInteractionHandle(): string
    {
        return $this->interactionHandle;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performInteraction($this);
    }
}
