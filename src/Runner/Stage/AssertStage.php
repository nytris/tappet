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

use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class AssertStage.
 *
 * Represents the Assertions stage of a scenario.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertStage extends AbstractStage
{
    /**
     * @param AssertionInterface[] $assertions
     */
    public function __construct(
        private readonly array $assertions
    ) {
    }

    /**
     * Fetches the assertions to be performed.
     *
     * @return AssertionInterface[]
     */
    public function getAssertions(): array
    {
        return $this->assertions;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        foreach ($this->getAssertions() as $assertion) {
            $assertion->perform($environment);
        }
    }
}
