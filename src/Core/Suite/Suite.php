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

class Suite implements SuiteInterface
{
    /**
     * @var string
     */
    private $description;
    /**
     * @var ScenarioInterface[]
     */
    private $scenarios;

    /**
     * @param ScenarioInterface[] $scenarios
     */
    public function __construct(string $description, array $scenarios)
    {
        $this->description = $description;
        $this->scenarios = $scenarios;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getScenarios(): array
    {
        return $this->scenarios;
    }
}
