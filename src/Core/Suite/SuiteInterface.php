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

namespace Tappet\Core\Suite;

use Tappet\Core\Scenario\ScenarioInterface;

interface SuiteInterface
{
    public function getDescription(): string;

    /**
     * @return ScenarioInterface[]
     */
    public function getScenarios(): array;
}
