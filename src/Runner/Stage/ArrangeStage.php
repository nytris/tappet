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

use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

class ArrangeStage extends AbstractStage
{
    /**
     * @param ArrangementInterface[] $arrangements
     */
    public function __construct(
        private readonly array $arrangements
    ) {
    }

    /**
     * @return ArrangementInterface[]
     */
    public function getArrangements(): array
    {
        return $this->arrangements;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        foreach ($this->getArrangements() as $arrangement) {
            $arrangement->perform($environment);
        }
    }
}
