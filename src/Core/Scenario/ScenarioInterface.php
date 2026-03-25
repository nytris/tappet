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

namespace Tappet\Core\Scenario;

use Tappet\Core\Stage\StageInterface;

interface ScenarioInterface
{
    public function getDescription(): string;

    /**
     * @return StageInterface[]
     */
    public function getStages(): array;

    public function perform(): void;
}
