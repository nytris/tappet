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

namespace Tappet\Runner\Action;

use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Step\StepInterface;

/**
 * Interface ActionInterface.
 *
 * Represents an action that can be performed during a scenario.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ActionInterface extends StepInterface
{
    /**
     * Performs the action.
     */
    public function perform(EnvironmentInterface $environment): void;
}
