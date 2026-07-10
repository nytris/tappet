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

namespace Tappet\Runner\Arrangement;

use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Step\StepInterface;

/**
 * Interface ArrangementInterface.
 *
 * Represents an arrangement that can be performed during a scenario to set up prerequisites.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ArrangementInterface extends StepInterface
{
    /**
     * Performs the arrangement.
     */
    public function perform(EnvironmentInterface $environment): void;
}
