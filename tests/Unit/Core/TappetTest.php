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

namespace Tappet\Tests\Unit\Core;

use Mockery\MockInterface;
use RuntimeException;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Scenario\Scenario;
use Tappet\Core\Suite\Suite;
use Tappet\Core\Suite\SuiteInterface;
use Tappet\Core\Tappet;
use Tappet\Tests\AbstractTestCase;

/**
 * Class TappetTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TappetTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        Tappet::uninitialise();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Tappet::uninitialise();
    }

    public function testDescribeThrowsWhenNoDescriberSet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No describer set');

        Tappet::describe('my suite', []);
    }

    public function testDescribeCallsDescriberWithNewSuite(): void
    {
        $capturedSuite = null;
        Tappet::initialise(function (SuiteInterface $suite) use (&$capturedSuite) {
            $capturedSuite = $suite;
        }, $this->environment);

        Tappet::describe('my suite', []);

        static::assertInstanceOf(Suite::class, $capturedSuite);
    }

    public function testDescribePassesCorrectDescriptionToSuite(): void
    {
        $capturedSuite = null;
        Tappet::initialise(function (SuiteInterface $suite) use (&$capturedSuite) {
            $capturedSuite = $suite;
        }, $this->environment);

        Tappet::describe('my suite', []);

        static::assertSame('my suite', $capturedSuite->getDescription());
    }

    public function testDescribePassesScenariosToSuite(): void
    {
        $capturedSuite = null;
        $scenario = new Scenario($this->environment, 'my scenario');
        Tappet::initialise(function (SuiteInterface $suite) use (&$capturedSuite) {
            $capturedSuite = $suite;
        }, $this->environment);

        Tappet::describe('my suite', [$scenario]);

        static::assertSame([$scenario], $capturedSuite->getScenarios());
    }

    public function testItReturnsScenarioInstance(): void
    {
        Tappet::initialise(fn () => null, $this->environment);

        $scenario = Tappet::it('my scenario');

        static::assertInstanceOf(Scenario::class, $scenario);
    }

    public function testItReturnsScenarioWithCorrectDescription(): void
    {
        Tappet::initialise(fn () => null, $this->environment);

        $scenario = Tappet::it('my scenario');

        static::assertSame('my scenario', $scenario->getDescription());
    }
}
