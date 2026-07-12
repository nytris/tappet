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
use Tappet\Runner\Matcher\MatcherInterface;

/**
 * Class ExpectList.
 *
 * Asserts that a list-like region contains the given items in order. Each value is a MatcherInterface
 * describing how the corresponding list item (e.g. <li> element) must match (e.g. Text, ExactText).
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectList implements RegionAssertionInterface
{
    /**
     * @param string $regionHandle
     * @param MatcherInterface[] $items Ordered list of expected item matchers.
     */
    public function __construct(
        private readonly string $regionHandle,
        private readonly array $items
    ) {
    }

    /**
     * Fetches the expected list items.
     *
     * @return MatcherInterface[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function getRegionHandle(): string
    {
        return $this->regionHandle;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performRegionAssertion($this);
    }
}
