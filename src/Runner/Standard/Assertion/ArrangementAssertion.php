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

use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class ArrangementAssertion.
 *
 * Performs an arrangement during the assertion stage of a scenario.
 *
 * Arrangements are normally only performed during the arrangement stage, but wrapping one
 * explicitly in an ArrangementAssertion makes it obvious when this unusual placement
 * is intentional.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ArrangementAssertion implements AssertionInterface
{
    public function __construct(
        private readonly ArrangementInterface $arrangement
    ) {
    }

    /**
     * Fetches the arrangement that will be performed.
     */
    public function getArrangement(): ArrangementInterface
    {
        return $this->arrangement;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $this->arrangement->perform($environment);
    }
}
