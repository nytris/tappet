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
use Tappet\Core\Arrangement\ArrangementInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Stage\ArrangeStage;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ArrangeStageTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ArrangeStageTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
    }

    public function testGetArrangementsReturnsArrangements(): void
    {
        $arrangement1 = mock(ArrangementInterface::class);
        $arrangement2 = mock(ArrangementInterface::class);
        $stage = new ArrangeStage([$arrangement1, $arrangement2]);

        static::assertSame([$arrangement1, $arrangement2], $stage->getArrangements());
    }

    public function testGetArrangementsReturnsEmptyArrayWhenNoneProvided(): void
    {
        $stage = new ArrangeStage([]);

        static::assertSame([], $stage->getArrangements());
    }

    public function testPerformPerformsEachArrangementInSequence(): void
    {
        $arrangement1 = mock(ArrangementInterface::class);
        $arrangement2 = mock(ArrangementInterface::class);
        $arrangement1->expects()
            ->perform($this->environment)
            ->once()
            ->globally()->ordered();
        $arrangement2->expects()
            ->perform($this->environment)
            ->once()
            ->globally()->ordered();
        $stage = new ArrangeStage([$arrangement1, $arrangement2]);

        $stage->perform($this->environment);
    }

    public function testPerformDoesNothingWhenNoArrangements(): void
    {
        $stage = new ArrangeStage([]);

        $this->expectNotToPerformAssertions();

        $stage->perform($this->environment);
    }
}
