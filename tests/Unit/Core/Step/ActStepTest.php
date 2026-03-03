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

namespace Tappet\Tests\Unit\Core\Step;

use Mockery\MockInterface;
use Tappet\Core\Action\ActionInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Step\ActStep;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ActStepTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ActStepTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
    }

    public function testGetActionsReturnsActions(): void
    {
        $action1 = mock(ActionInterface::class);
        $action2 = mock(ActionInterface::class);
        $step = new ActStep([$action1, $action2]);

        static::assertSame([$action1, $action2], $step->getActions());
    }

    public function testGetActionsReturnsEmptyArrayWhenNoneProvided(): void
    {
        $step = new ActStep([]);

        static::assertSame([], $step->getActions());
    }

    public function testPerformPerformsEachActionInSequence(): void
    {
        $action1 = mock(ActionInterface::class);
        $action2 = mock(ActionInterface::class);
        $action1->expects()
            ->perform($this->environment)
            ->once()
            ->globally()->ordered();
        $action2->expects()
            ->perform($this->environment)
            ->once()
            ->globally()->ordered();
        $step = new ActStep([$action1, $action2]);

        $step->perform($this->environment);
    }

    public function testPerformDoesNothingWhenNoActions(): void
    {
        $step = new ActStep([]);

        $this->expectNotToPerformAssertions();

        $step->perform($this->environment);
    }
}
