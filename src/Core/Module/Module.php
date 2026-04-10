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

namespace Tappet\Core\Module;

use Tappet\Core\Scenario\ScenarioInterface;

/**
 * Class Module.
 *
 * Represents a module of scenarios.
 *
 * Sometimes referred to as a "suite" in other testing frameworks,
 * but in Tappet "suite" refers to the entire test project.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Module implements ModuleInterface
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

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getScenarios(): array
    {
        return $this->scenarios;
    }
}
