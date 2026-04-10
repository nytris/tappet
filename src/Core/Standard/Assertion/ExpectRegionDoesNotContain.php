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

namespace Tappet\Core\Standard\Assertion;

use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

/**
 * Class ExpectRegionDoesNotContain.
 *
 * Asserts that the given text is not found in the given region.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectRegionDoesNotContain implements RegionAssertionInterface
{
    /**
     * @var string
     */
    private $regionHandle;
    /**
     * @var string
     */
    private $text;

    public function __construct(string $regionHandle, string $text)
    {
        $this->regionHandle = $regionHandle;
        $this->text = $text;
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
