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

namespace Tappet\Runner\Standard\Arrangement;

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class ActionArrangement.
 *
 * Performs an action during the arrangement stage of a scenario.
 *
 * Actions are normally only performed during the act stage, but wrapping one explicitly
 * in an ActionArrangement makes it obvious when this is being done as part of setup,
 * for example dismissing a cookie banner before the scenario's real interactions begin.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ActionArrangement implements ArrangementInterface
{
    public function __construct(
        private readonly ActionInterface $action
    ) {
    }

    /**
     * Fetches the action that will be performed.
     */
    public function getAction(): ActionInterface
    {
        return $this->action;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $this->action->perform($environment);
    }
}
