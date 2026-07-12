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
 * Class ExpectTable.
 *
 * Asserts that a table region contains the given rows of data. Each row is an associative array
 * mapping column handles to a MatcherInterface describing how the corresponding data cell must match
 * (e.g. Text, ExactText). A column handle is matched by the `data-ui-column` attribute on the heading
 * cell (e.g. `<th>` or `<td>`) only, not repeated on every data cell in every row - the data cell to
 * match is resolved by that heading cell's position within its row.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectTable implements RegionAssertionInterface
{
    /**
     * @param string $regionHandle
     * @param array<string, MatcherInterface>[] $rows Ordered list of rows; each row maps column handle to matcher.
     */
    public function __construct(
        private readonly string $regionHandle,
        private readonly array $rows
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
     * Fetches the expected rows.
     *
     * @return array<string, MatcherInterface>[]
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performRegionAssertion($this);
    }
}
