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

namespace Tappet\Runner\Scenario;

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Stage\StageInterface;

/**
 * Interface ScenarioInterface.
 *
 * Represents a single test scenario in a test module.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ScenarioInterface
{
    public function act(ActionInterface ...$actions): ScenarioInterface;

    /**
     * Performs post-scenario validation. Called by the test runner after each scenario
     * via the automation layer's afterEach hook (e.g. Mocha's afterEach in Cypress).
     * Asserts that no unexpected transitions remain unconsumed in the log.
     */
    public function after(): void;

    public function arrange(ArrangementInterface ...$arrangements): ScenarioInterface;

    public function assert(AssertionInterface ...$assertions): ScenarioInterface;

    /**
     * Fetches the human-readable description of the scenario.
     */
    public function getDescription(): string;

    /**
     * @return StageInterface[]
     */
    public function getStages(): array;

    public function perform(): void;
}
