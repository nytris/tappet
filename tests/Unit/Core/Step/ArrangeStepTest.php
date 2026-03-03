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
use Tappet\Core\Arrangement\ArrangementInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Step\ArrangeStep;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ArrangeStepTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ArrangeStepTest extends AbstractTestCase
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
        $step = new ArrangeStep([$arrangement1, $arrangement2]);

        static::assertSame([$arrangement1, $arrangement2], $step->getArrangements());
    }

    public function testGetArrangementsReturnsEmptyArrayWhenNoneProvided(): void
    {
        $step = new ArrangeStep([]);

        static::assertSame([], $step->getArrangements());
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
        $step = new ArrangeStep([$arrangement1, $arrangement2]);

        $step->perform($this->environment);
    }

    public function testPerformDoesNothingWhenNoArrangements(): void
    {
        $step = new ArrangeStep([]);

        $this->expectNotToPerformAssertions();

        $step->perform($this->environment);
    }
}
