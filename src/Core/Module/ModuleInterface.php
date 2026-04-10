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
 * Interface ModuleInterface.
 *
 * Represents a module of scenarios.
 *
 * Sometimes referred to as a "suite" in other testing frameworks,
 * but in Tappet "suite" refers to the entire test project.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ModuleInterface
{
    /**
     * Fetches the description of the module.
     */
    public function getDescription(): string;

    /**
     * Fetches the scenarios in the module.
     *
     * @return ScenarioInterface[]
     */
    public function getScenarios(): array;
}
