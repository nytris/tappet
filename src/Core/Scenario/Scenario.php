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

use Tappet\Core\Action\ActionInterface;
use Tappet\Core\Arrangement\ArrangementInterface;
use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Step\ActStep;
use Tappet\Core\Step\ArrangeStep;
use Tappet\Core\Step\AssertStep;
use Tappet\Core\Step\StepInterface;

class Scenario implements ScenarioInterface
{
    /**
     * @var string
     */
    public $description;
    /**
     * @var EnvironmentInterface
     */
    private $environment;
    /**
     * @var StepInterface[]
     */
    private $steps = [];

    public function __construct(EnvironmentInterface $environment, string $description)
    {
        $this->description = $description;
        $this->environment = $environment;
    }

    public function act(ActionInterface ...$actions): ScenarioInterface
    {
        $this->steps[] = new ActStep($actions);

        return $this;
    }

    public function arrange(ArrangementInterface ...$arrangements): ScenarioInterface
    {
        $this->steps[] = new ArrangeStep($arrangements);

        return $this;
    }

    public function assert(AssertionInterface ...$assertions): ScenarioInterface
    {
        $this->steps[] = new AssertStep($assertions);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    public function perform(): void
    {
        foreach ($this->steps as $step) {
            $step->perform($this->environment);
        }
    }
}
