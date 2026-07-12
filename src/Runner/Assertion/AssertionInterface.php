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

namespace Tappet\Runner\Assertion;

use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Step\StepInterface;

/**
 * Interface AssertionInterface.
 *
 * Represents an assertion that can be performed during a scenario.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface AssertionInterface extends StepInterface
{
    /**
     * Performs the assertion.
     */
    public function perform(EnvironmentInterface $environment): void;
}
