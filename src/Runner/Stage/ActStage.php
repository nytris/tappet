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

namespace Tappet\Runner\Stage;

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

class ActStage extends AbstractStage
{
    /**
     * @param ActionInterface[] $actions
     */
    public function __construct(
        private readonly array $actions
    ) {
    }

    /**
     * @return ActionInterface[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        foreach ($this->getActions() as $action) {
            $action->perform($environment);
        }
    }
}
