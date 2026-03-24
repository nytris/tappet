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

namespace Tappet\Tests\Unit\Core\Standard\Arrangement;

use Mockery\MockInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Standard\Arrangement\LoadFixture;
use Tappet\Tests\AbstractTestCase;

/**
 * Class LoadFixtureTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class LoadFixtureTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    /** @var FixtureInterface<ModelInterface>&MockInterface */
    private FixtureInterface&MockInterface $fixture;
    private LoadFixture $arrangement;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
        $this->fixture = mock(FixtureInterface::class);

        $this->arrangement = new LoadFixture('myHandle', $this->fixture);
    }

    public function testGetFixtureReturnsFixture(): void
    {
        static::assertSame($this->fixture, $this->arrangement->getFixture());
    }

    public function testGetHandleReturnsHandle(): void
    {
        static::assertSame('myHandle', $this->arrangement->getHandle());
    }

    public function testPerformCallsEnvironmentLoadFixtureWithHandleAndFixture(): void
    {
        $this->environment->expects()
            ->loadFixture('myHandle', $this->fixture)
            ->once();

        $this->arrangement->perform($this->environment);
    }
}
