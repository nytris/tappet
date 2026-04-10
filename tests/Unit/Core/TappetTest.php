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
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Exception\LogicException;
use Tappet\Core\Module\Module;
use Tappet\Core\Module\ModuleInterface;
use Tappet\Core\Scenario\Scenario;
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
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Nytris Tappet ::describe() :: No describer set');

        Tappet::describe('my module', []);
    }

    public function testDescribeCallsDescriberWithNewModule(): void
    {
        $capturedModule = null;
        Tappet::initialise(function (ModuleInterface $module) use (&$capturedModule) {
            $capturedModule = $module;
        }, $this->environment);

        Tappet::describe('my module', []);

        static::assertInstanceOf(Module::class, $capturedModule);
    }

    public function testDescribePassesCorrectDescriptionToModule(): void
    {
        $capturedModule = null;
        Tappet::initialise(function (ModuleInterface $module) use (&$capturedModule) {
            $capturedModule = $module;
        }, $this->environment);

        Tappet::describe('my module', []);

        static::assertSame('my module', $capturedModule->getDescription());
    }

    public function testDescribePassesScenariosToModule(): void
    {
        $capturedModule = null;
        $scenario = new Scenario($this->environment, 'my scenario');
        Tappet::initialise(function (ModuleInterface $module) use (&$capturedModule) {
            $capturedModule = $module;
        }, $this->environment);

        Tappet::describe('my module', [$scenario]);

        static::assertSame([$scenario], $capturedModule->getScenarios());
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

    public function testUninitialiseResetsDescriberSoSubsequentDescribeThrows(): void
    {
        Tappet::initialise(fn () => null, $this->environment);
        Tappet::uninitialise();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Nytris Tappet ::describe() :: No describer set');

        Tappet::describe('my module', []);
    }
}
