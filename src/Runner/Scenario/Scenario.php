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
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Stage\ActStage;
use Tappet\Runner\Stage\ArrangeStage;
use Tappet\Runner\Stage\AssertStage;
use Tappet\Runner\Stage\StageInterface;

/**
 * Class Scenario.
 *
 * Represents a single test scenario in a test module.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Scenario implements ScenarioInterface
{
    /**
     * @var StageInterface[]
     */
    private array $stages = [];

    public function __construct(
        private readonly EnvironmentInterface $environment,
        private readonly string $description
    ) {
    }

    public function act(ActionInterface ...$actions): ScenarioInterface
    {
        $this->stages[] = new ActStage($actions);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function after(): void
    {
        $this->environment->assertTransitionLogEmpty();
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

    /**
     * @inheritDoc
     */
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
