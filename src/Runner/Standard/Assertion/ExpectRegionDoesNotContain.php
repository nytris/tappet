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

use Tappet\Runner\Assertion\RegionAssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class ExpectRegionDoesNotContain.
 *
 * Asserts that the given text is not found in the given region.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectRegionDoesNotContain implements RegionAssertionInterface
{
    public function __construct(
        private readonly string $regionHandle,
        private readonly string $text
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getRegionHandle(): string
    {
        return $this->regionHandle;
    }

    /**
     * Fetches the text expected not to be found in the region.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performRegionAssertion($this);
    }
}
