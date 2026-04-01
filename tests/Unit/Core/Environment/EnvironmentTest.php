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

namespace Tappet\Tests\Unit\Core\Environment;

use Mockery\MockInterface;
use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Environment\Environment;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Fixture\ModelRepositoryInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class EnvironmentTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EnvironmentTest extends AbstractTestCase
{
    private AutomationInterface&MockInterface $automation;
    private Environment $environment;
    private ModelRepositoryInterface&MockInterface $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = mock(AutomationInterface::class);
        $this->modelRepository = mock(ModelRepositoryInterface::class);

        $this->environment = new Environment($this->modelRepository, $this->automation, 'https://my-app.example.com');
    }

    public function testAssertPageDelegatesToAutomation(): void
    {
        $this->automation->expects()
            ->assertPage('https://example.com/dashboard', $this->environment)
            ->once();

        $this->environment->assertPage('https://example.com/dashboard');
    }

    public function testGetAutomationReturnsTheAutomationLayerAbstraction(): void
    {
        static::assertSame($this->automation, $this->environment->getAutomation());
    }

    public function testGetBaseUrlReturnsBaseUrl(): void
    {
        static::assertSame('https://my-app.example.com', $this->environment->getBaseUrl());
    }

    public function testGetFixtureModelDelegatesToModelRepository(): void
    {
        $model = mock(ModelInterface::class);

        $this->modelRepository->expects()
            ->getFixtureModel(ModelInterface::class, 'myHandle')
            ->once()
            ->andReturn($model);

        $result = $this->environment->getFixtureModel(ModelInterface::class, 'myHandle');

        static::assertSame($model, $result);
    }

    public function testLoadFixtureDelegatesToModelRepository(): void
    {
        $fixture = mock(FixtureInterface::class);

        $this->modelRepository->expects()
            ->loadFixture('myHandle', $fixture)
            ->once();

        $this->environment->loadFixture('myHandle', $fixture);
    }

    public function testLoadMultipleFixturesDelegatesToModelRepository(): void
    {
        $fixture1 = mock(FixtureInterface::class);
        $fixture2 = mock(FixtureInterface::class);
        $fixtures = ['myFirstHandle' => $fixture1, 'mySecondHandle' => $fixture2];

        $this->modelRepository->expects()
            ->loadMultipleFixtures($fixtures)
            ->once();

        $this->environment->loadMultipleFixtures($fixtures);
    }

    public function testPerformFieldActionDelegatesToAutomation(): void
    {
        $action = mock(FieldActionInterface::class);

        $this->automation->expects()
            ->performFieldAction($action)
            ->once();

        $this->environment->performFieldAction($action);
    }

    public function testPerformInteractionDelegatesToAutomation(): void
    {
        $interaction = mock(InteractionInterface::class);

        $this->automation->expects()
            ->performInteraction($interaction)
            ->once();

        $this->environment->performInteraction($interaction);
    }

    public function testPerformRegionAssertionDelegatesToAutomation(): void
    {
        $assertion = mock(RegionAssertionInterface::class);

        $this->automation->expects()
            ->performRegionAssertion($assertion)
            ->once();

        $this->environment->performRegionAssertion($assertion);
    }

    public function testVisitPageDelegatesToAutomation(): void
    {
        $this->automation->expects()
            ->visitPage('https://example.com/login')
            ->once();

        $this->environment->visitPage('https://example.com/login');
    }
}
