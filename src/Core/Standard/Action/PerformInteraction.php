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

namespace Tappet\Core\Standard\Action;

use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

class PerformInteraction implements InteractionInterface
{
    /**
     * @var string
     */
    private $interactionHandle;

    public function __construct(string $interactionHandle)
    {
        $this->interactionHandle = $interactionHandle;
    }

    public function getInteractionHandle(): string
    {
        return $this->interactionHandle;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performInteraction($this);
    }
}
