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

namespace Tappet\Core\Step;

use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

class AssertStep extends AbstractStep
{
    /**
     * @var AssertionInterface[]
     */
    private $assertions;

    /**
     * @param AssertionInterface[] $assertions
     */
    public function __construct(array $assertions)
    {
        $this->assertions = $assertions;
    }

    /**
     * @return AssertionInterface[]
     */
    public function getAssertions(): array
    {
        return $this->assertions;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        foreach ($this->getAssertions() as $assertion) {
            $assertion->perform($environment);
        }
    }
}
