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

use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class AssertionArrangement.
 *
 * Performs an assertion during the arrangement stage of a scenario.
 *
 * Assertions that do not otherwise implement ArrangementInterface directly can be wrapped
 * explicitly in an AssertionArrangement to make it obvious when they are being checked
 * as a precondition rather than as the outcome of the scenario being verified.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertionArrangement implements ArrangementInterface
{
    public function __construct(
        private readonly AssertionInterface $assertion
    ) {
    }

    /**
     * Fetches the assertion that will be performed.
     */
    public function getAssertion(): AssertionInterface
    {
        return $this->assertion;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $this->assertion->perform($environment);
    }
}
