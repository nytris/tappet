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

namespace Tappet\Core\Stage;

use Tappet\Core\Action\ActionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

class ActStage extends AbstractStage
{
    /**
     * @var ActionInterface[]
     */
    private $actions;

    /**
     * @param ActionInterface[] $actions
     */
    public function __construct(array $actions)
    {
        $this->actions = $actions;
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

            // FIXME: Assert that the current URL matches the currently expected one,
            //        which needs to be explicitly given by the OpenPage arrangement or ExpectNewPage assertion.
        }
    }
}
