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
use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Stage\AssertStage;
use Tappet\Tests\AbstractTestCase;

/**
 * Class AssertStageTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertStageTest extends AbstractTestCase
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
        $stage = new AssertStage([$assertion1, $assertion2]);

        static::assertSame([$assertion1, $assertion2], $stage->getAssertions());
    }

    public function testGetAssertionsReturnsEmptyArrayWhenNoneProvided(): void
    {
        $stage = new AssertStage([]);

        static::assertSame([], $stage->getAssertions());
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
        $stage = new AssertStage([$assertion1, $assertion2]);

        $stage->perform($this->environment);
    }

    public function testPerformDoesNothingWhenNoAssertions(): void
    {
        $stage = new AssertStage([]);

        $this->expectNotToPerformAssertions();

        $stage->perform($this->environment);
    }
}
