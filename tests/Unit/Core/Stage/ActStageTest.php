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

namespace Tappet\Tests\Unit\Core\Stage;

use Mockery\MockInterface;
use Tappet\Core\Action\ActionInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Stage\ActStage;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ActStageTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ActStageTest extends AbstractTestCase
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
        $stage = new ActStage([$action1, $action2]);

        static::assertSame([$action1, $action2], $stage->getActions());
    }

    public function testGetActionsReturnsEmptyArrayWhenNoneProvided(): void
    {
        $stage = new ActStage([]);

        static::assertSame([], $stage->getActions());
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
        $stage = new ActStage([$action1, $action2]);

        $stage->perform($this->environment);
    }

    public function testPerformDoesNothingWhenNoActions(): void
    {
        $stage = new ActStage([]);

        $this->expectNotToPerformAssertions();

        $stage->perform($this->environment);
    }
}
