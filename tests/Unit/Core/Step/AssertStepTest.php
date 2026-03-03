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
use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Step\AssertStep;
use Tappet\Tests\AbstractTestCase;

/**
 * Class AssertStepTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertStepTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
    }

    public function testGetAssertionsReturnsAssertions(): void
    {
        $assertion1 = mock(AssertionInterface::class);
        $assertion2 = mock(AssertionInterface::class);
        $step = new AssertStep([$assertion1, $assertion2]);

        static::assertSame([$assertion1, $assertion2], $step->getAssertions());
    }

    public function testGetAssertionsReturnsEmptyArrayWhenNoneProvided(): void
    {
        $step = new AssertStep([]);

        static::assertSame([], $step->getAssertions());
    }

    public function testPerformPerformsEachAssertionInSequence(): void
    {
        $assertion1 = mock(AssertionInterface::class);
        $assertion2 = mock(AssertionInterface::class);
        $assertion1->expects()
            ->perform($this->environment)
            ->once()
            ->globally()->ordered();
        $assertion2->expects()
            ->perform($this->environment)
            ->once()
            ->globally()->ordered();
        $step = new AssertStep([$assertion1, $assertion2]);

        $step->perform($this->environment);
    }

    public function testPerformDoesNothingWhenNoAssertions(): void
    {
        $step = new AssertStep([]);

        $this->expectNotToPerformAssertions();

        $step->perform($this->environment);
    }
}
