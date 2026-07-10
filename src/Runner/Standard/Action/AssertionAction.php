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

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class AssertionAction.
 *
 * Performs an assertion during the act stage of a scenario.
 *
 * In general, assertions should only be performed during the assertion stage,
 * but sometimes for efficiency's sake it can make sense to perform assertions during act.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertionAction implements ActionInterface
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
