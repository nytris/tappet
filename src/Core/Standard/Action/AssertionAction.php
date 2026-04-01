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

use Tappet\Core\Action\ActionInterface;
use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

/**
 * Class AssertionAction.
 *
 * Performs an assertion during the act stage of a scenario.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertionAction implements ActionInterface
{
    /**
     * @var AssertionInterface
     */
    private $assertion;

    public function __construct(AssertionInterface $assertion)
    {
        $this->assertion = $assertion;
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
