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
use Tappet\Core\Step\ActStep;
use Tappet\Core\Step\ArrangeStep;
use Tappet\Core\Step\AssertStep;
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

    public function testActAddsAnActStep(): void
    {
        $action = mock(ActionInterface::class);

        $this->scenario->act($action);

        static::assertCount(1, $this->scenario->getSteps());
        static::assertInstanceOf(ActStep::class, $this->scenario->getSteps()[0]);
    }

    public function testActReturnsSelfForFluentInterface(): void
    {
        $action = mock(ActionInterface::class);

        $result = $this->scenario->act($action);

        static::assertSame($this->scenario, $result);
    }

    public function testArrangeAddsAnArrangeStep(): void
    {
        $arrangement = mock(ArrangementInterface::class);

        $this->scenario->arrange($arrangement);

        static::assertCount(1, $this->scenario->getSteps());
        static::assertInstanceOf(ArrangeStep::class, $this->scenario->getSteps()[0]);
    }

    public function testArrangeReturnsSelfForFluentInterface(): void
    {
        $arrangement = mock(ArrangementInterface::class);

        $result = $this->scenario->arrange($arrangement);

        static::assertSame($this->scenario, $result);
    }

    public function testAssertAddsAnAssertStep(): void
    {
        $assertion = mock(AssertionInterface::class);

        $this->scenario->assert($assertion);

        static::assertCount(1, $this->scenario->getSteps());
        static::assertInstanceOf(AssertStep::class, $this->scenario->getSteps()[0]);
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

    public function testGetStepsReturnsEmptyArrayInitially(): void
    {
        static::assertSame([], $this->scenario->getSteps());
    }

    public function testGetStepsReturnsAllAddedStepsInOrder(): void
    {
        $arrangement = mock(ArrangementInterface::class);
        $action = mock(ActionInterface::class);
        $assertion = mock(AssertionInterface::class);

        $this->scenario->arrange($arrangement);
        $this->scenario->act($action);
        $this->scenario->assert($assertion);

        $steps = $this->scenario->getSteps();
        static::assertCount(3, $steps);
        static::assertInstanceOf(ArrangeStep::class, $steps[0]);
        static::assertInstanceOf(ActStep::class, $steps[1]);
        static::assertInstanceOf(AssertStep::class, $steps[2]);
    }

    public function testPerformPerformsAllStepsWithEnvironment(): void
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
