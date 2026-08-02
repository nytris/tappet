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

namespace Tappet\Runner\Standard\Assertion;

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class ActionAssertion.
 *
 * Performs an action during the assertion stage of a scenario.
 *
 * Actions are normally only performed during the act stage, but wrapping one explicitly
 * in an ActionAssertion makes it obvious when this unusual placement is intentional.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ActionAssertion implements AssertionInterface
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
