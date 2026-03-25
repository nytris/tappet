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
use Tappet\Core\Stage\ActStage;
use Tappet\Core\Stage\ArrangeStage;
use Tappet\Core\Stage\AssertStage;
use Tappet\Core\Stage\StageInterface;

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
     * @var StageInterface[]
     */
    private $stages = [];

    public function __construct(EnvironmentInterface $environment, string $description)
    {
        $this->description = $description;
        $this->environment = $environment;
    }

    public function act(ActionInterface ...$actions): ScenarioInterface
    {
        $this->stages[] = new ActStage($actions);

        return $this;
    }

    public function arrange(ArrangementInterface ...$arrangements): ScenarioInterface
    {
        $this->stages[] = new ArrangeStage($arrangements);

        return $this;
    }

    public function assert(AssertionInterface ...$assertions): ScenarioInterface
    {
        $this->stages[] = new AssertStage($assertions);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStages(): array
    {
        return $this->stages;
    }

    public function perform(): void
    {
        foreach ($this->stages as $stage) {
            $stage->perform($this->environment);
        }
    }
}
