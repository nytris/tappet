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

namespace Tappet\Tests\Unit\Core\Scenario;

use Mockery\MockInterface;
use Tappet\Core\Action\ActionInterface;
use Tappet\Core\Arrangement\ArrangementInterface;
use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Scenario\Scenario;
use Tappet\Core\Stage\ActStage;
use Tappet\Core\Stage\ArrangeStage;
use Tappet\Core\Stage\AssertStage;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ScenarioTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ScenarioTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private Scenario $scenario;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->scenario = new Scenario($this->environment, 'my scenario description');
    }

    public function testActAddsAnActStage(): void
    {
        $action = mock(ActionInterface::class);

        $this->scenario->act($action);

        static::assertCount(1, $this->scenario->getStages());
        static::assertInstanceOf(ActStage::class, $this->scenario->getStages()[0]);
    }

    public function testActReturnsSelfForFluentInterface(): void
    {
        $action = mock(ActionInterface::class);

        $result = $this->scenario->act($action);

        static::assertSame($this->scenario, $result);
    }

    public function testArrangeAddsAnArrangeStage(): void
    {
        $arrangement = mock(ArrangementInterface::class);

        $this->scenario->arrange($arrangement);

        static::assertCount(1, $this->scenario->getStages());
        static::assertInstanceOf(ArrangeStage::class, $this->scenario->getStages()[0]);
    }

    public function testArrangeReturnsSelfForFluentInterface(): void
    {
        $arrangement = mock(ArrangementInterface::class);

        $result = $this->scenario->arrange($arrangement);

        static::assertSame($this->scenario, $result);
    }

    public function testAssertAddsAnAssertStage(): void
    {
        $assertion = mock(AssertionInterface::class);

        $this->scenario->assert($assertion);

        static::assertCount(1, $this->scenario->getStages());
        static::assertInstanceOf(AssertStage::class, $this->scenario->getStages()[0]);
    }

    public function testAssertReturnsSelfForFluentInterface(): void
    {
        $assertion = mock(AssertionInterface::class);

        $result = $this->scenario->assert($assertion);

        static::assertSame($this->scenario, $result);
    }

    public function testGetDescriptionReturnsDescription(): void
    {
        static::assertSame('my scenario description', $this->scenario->getDescription());
    }

    public function testGetStagesReturnsEmptyArrayInitially(): void
    {
        static::assertSame([], $this->scenario->getStages());
    }

    public function testGetStagesReturnsAllAddedStagesInOrder(): void
    {
        $arrangement = mock(ArrangementInterface::class);
        $action = mock(ActionInterface::class);
        $assertion = mock(AssertionInterface::class);

        $this->scenario->arrange($arrangement);
        $this->scenario->act($action);
        $this->scenario->assert($assertion);

        $stages = $this->scenario->getStages();
        static::assertCount(3, $stages);
        static::assertInstanceOf(ArrangeStage::class, $stages[0]);
        static::assertInstanceOf(ActStage::class, $stages[1]);
        static::assertInstanceOf(AssertStage::class, $stages[2]);
    }

    public function testPerformPerformsAllStagesWithEnvironment(): void
    {
        $arrangement = mock(ArrangementInterface::class);
        $action = mock(ActionInterface::class);
        $assertion = mock(AssertionInterface::class);
        $arrangement->expects()
            ->perform($this->environment)
            ->once();
        $action->expects()
            ->perform($this->environment)
            ->once();
        $assertion->expects()
            ->perform($this->environment)
            ->once();

        $this->scenario->arrange($arrangement);
        $this->scenario->act($action);
        $this->scenario->assert($assertion);
        $this->scenario->perform();
    }
}
