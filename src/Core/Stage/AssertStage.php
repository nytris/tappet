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

namespace Tappet\Core\Stage;

use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

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
            // FIXME: Assert that the current URL matches the currently expected one,
            //        which needs to be explicitly given by the OpenPage arrangement or ExpectNewPage assertion.
            //        To be stored inside the Environment.

            $assertion->perform($environment);
        }
    }
}
