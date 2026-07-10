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

namespace Tappet\Tests\Unit\Runner\Environment;

use Mockery;
use Mockery\MockInterface;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Assertion\RegionAssertionInterface;
use Tappet\Runner\Assertion\StateAssertionInterface;
use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Runner\Configuration\ConfigurationInterface;
use Tappet\Runner\Environment\Environment;
use Tappet\Runner\Fixture\ModelRepositoryInterface;
use Tappet\Runner\Page\PageInterface;
use Tappet\Runner\Transition\NavigationTransition;
use Tappet\Runner\Transition\TransitionInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class EnvironmentTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EnvironmentTest extends AbstractTestCase
{
    private AutomationInterface&MockInterface $automation;
    private ConfigurationInterface&MockInterface $configuration;
    private Environment $environment;
    private ModelRepositoryInterface&MockInterface $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = mock(AutomationInterface::class);
        $this->configuration = mock(ConfigurationInterface::class, [
            'getBaseUrl' => 'https://my-app.example.com',
        ]);
        $this->modelRepository = mock(ModelRepositoryInterface::class);

        $this->environment = new Environment($this->modelRepository, $this->automation, $this->configuration);
    }

    public function testAssertTransitionSetsCurrentTransitionAndQueuesWaitForTransition(): void
    {
        $transition = mock(TransitionInterface::class);

        $this->automation->expects()
            ->waitForTransition($transition)
            ->once();

        $this->environment->assertTransition($transition);
    }

    public function testAssertTransitionWaitsImmediately(): void
    {
        $transition = mock(TransitionInterface::class);

        $this->automation->allows()
            ->waitForTransition($transition)
            ->once();

        $this->environment->assertTransition($transition);
    }

    public function testAssertTransitionLogEmptyDelegatesToAutomation(): void
    {
        $this->automation->expects()
            ->assertTransitionLogEmpty()
            ->once();

        $this->environment->assertTransitionLogEmpty();
    }

    public function testBuildFullyQualifiedUrlPassesAFullyQualifiedUrlUnchanged(): void
    {
        static::assertSame(
            'https://example.com/login',
            $this->environment->buildFullyQualifiedUrl('https://example.com/login')
        );
    }

    public function testBuildFullyQualifiedUrlPrependsBaseUrlToARelativeUrl(): void
    {
        static::assertSame(
            'https://my-app.example.com/login',
            $this->environment->buildFullyQualifiedUrl('/login')
        );
    }

    public function testBuildPageUrlBuildsTheAbsoluteUrlForThePage(): void
    {
        $page = mock(PageInterface::class);
        $page->allows()
            ->buildUrl($this->environment)
            ->andReturn('/dashboard');

        static::assertSame(
            'https://my-app.example.com/dashboard',
            $this->environment->buildPageUrl($page)
        );
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
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->performFieldAction($action)
            ->once();

        $this->environment->performFieldAction($action);
    }

    public function testPerformFieldActionChecksTransitionLogEmptyWhenNoTransitionPending(): void
    {
        $action = mock(FieldActionInterface::class);
        $this->automation->allows()
            ->performFieldAction($action);

        $this->automation->expects()
            ->assertTransitionLogEmpty()
            ->once();

        $this->environment->performFieldAction($action);
    }

    public function testPerformFieldActionWaitsForTransitionAfterVisitUrl(): void
    {
        $action = mock(FieldActionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/form');

        $this->automation->expects()
            ->waitForTransition(Mockery::on(function (TransitionInterface $transition) {
                return $transition instanceof NavigationTransition
                    && $transition->getUrl() === 'https://my-app.example.com/form';
            }))
            ->once()
            ->globally()->ordered();
        $this->automation->expects()
            ->performFieldAction($action)
            ->once()
            ->globally()->ordered();

        $this->environment->visitUrl('/form');
        $this->environment->performFieldAction($action);
    }

    public function testPerformFieldAssertionDelegatesToAutomation(): void
    {
        $assertion = mock(FieldAssertionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->performFieldAssertion($assertion)
            ->once();

        $this->environment->performFieldAssertion($assertion);
    }

    public function testPerformFieldAssertionWaitsForTransitionAfterVisitUrl(): void
    {
        $assertion = mock(FieldAssertionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/form');

        $this->automation->expects()
            ->waitForTransition(Mockery::on(function (TransitionInterface $transition) {
                return $transition instanceof NavigationTransition
                    && $transition->getUrl() === 'https://my-app.example.com/form';
            }))
            ->once()
            ->globally()->ordered();
        $this->automation->expects()
            ->performFieldAssertion($assertion)
            ->once()
            ->globally()->ordered();

        $this->environment->visitUrl('/form');
        $this->environment->performFieldAssertion($assertion);
    }

    public function testPerformInteractionDelegatesToAutomation(): void
    {
        $interaction = mock(InteractionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->performInteraction($interaction)
            ->once();

        $this->environment->performInteraction($interaction);
    }

    public function testPerformInteractionWaitsForTransitionAfterVisitUrl(): void
    {
        $interaction = mock(InteractionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/page');

        $this->automation->expects()
            ->waitForTransition(Mockery::on(function (TransitionInterface $transition) {
                return $transition instanceof NavigationTransition
                    && $transition->getUrl() === 'https://my-app.example.com/page';
            }))
            ->once()
            ->globally()->ordered();
        $this->automation->expects()
            ->performInteraction($interaction)
            ->once()
            ->globally()->ordered();

        $this->environment->visitUrl('/page');
        $this->environment->performInteraction($interaction);
    }

    public function testPerformRegionAssertionDelegatesToAutomation(): void
    {
        $assertion = mock(RegionAssertionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->performRegionAssertion($assertion)
            ->once();

        $this->environment->performRegionAssertion($assertion);
    }

    public function testPerformRegionAssertionWaitsForTransitionAfterVisitUrl(): void
    {
        $assertion = mock(RegionAssertionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/page');

        $this->automation->expects()
            ->waitForTransition(Mockery::on(function (TransitionInterface $transition) {
                return $transition instanceof NavigationTransition
                    && $transition->getUrl() === 'https://my-app.example.com/page';
            }))
            ->once()
            ->globally()->ordered();
        $this->automation->expects()
            ->performRegionAssertion($assertion)
            ->once()
            ->globally()->ordered();

        $this->environment->visitUrl('/page');
        $this->environment->performRegionAssertion($assertion);
    }

    public function testPerformStateAssertionDelegatesToAutomation(): void
    {
        $assertion = mock(StateAssertionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->performStateAssertion($assertion)
            ->once();

        $this->environment->performStateAssertion($assertion);
    }

    public function testPerformStateAssertionWaitsForTransitionAfterVisitUrl(): void
    {
        $assertion = mock(StateAssertionInterface::class);
        $this->automation->allows()
            ->assertTransitionLogEmpty();
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/page');

        $this->automation->expects()
            ->waitForTransition(Mockery::on(function (TransitionInterface $transition) {
                return $transition instanceof NavigationTransition
                    && $transition->getUrl() === 'https://my-app.example.com/page';
            }))
            ->once()
            ->globally()->ordered();
        $this->automation->expects()
            ->performStateAssertion($assertion)
            ->once()
            ->globally()->ordered();

        $this->environment->visitUrl('/page');
        $this->environment->performStateAssertion($assertion);
    }

    public function testVisitUrlWithAbsoluteUrlPassesItUnchangedToAutomation(): void
    {
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->visitPage('https://example.com/login')
            ->once();

        $this->environment->visitUrl('https://example.com/login');
    }

    public function testVisitUrlWithRelativeUrlPrependsBaseUrl(): void
    {
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->visitPage('https://my-app.example.com/login')
            ->once();

        $this->environment->visitUrl('/login');
    }

    public function testVisitUrlChecksTransitionLogIsEmptyWhenNoPriorTransitionPending(): void
    {
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/login');

        $this->automation->expects()
            ->assertTransitionLogEmpty()
            ->once();

        $this->environment->visitUrl('/login');
    }

    public function testVisitUrlAfterAssertTransitionOnlyChecksTransitionLogIsEmpty(): void
    {
        $transition = mock(TransitionInterface::class);
        $this->automation->allows()
            ->waitForTransition($transition);
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/next');

        $this->automation->expects()
            ->assertTransitionLogEmpty()
            ->once();

        $this->environment->assertTransition($transition);
        $this->environment->visitUrl('/next');
    }

    public function testVisitUrlWaitsForTransitionFromPriorVisitUrl(): void
    {
        $this->automation->allows()
            ->assertTransitionLogEmpty();
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/page-one');
        $this->automation->allows()
            ->visitPage('https://my-app.example.com/page-two');

        $this->automation->expects()
            ->waitForTransition(Mockery::on(function (TransitionInterface $transition) {
                return $transition instanceof NavigationTransition
                    && $transition->getUrl() === 'https://my-app.example.com/page-one';
            }))
            ->once();

        $this->environment->visitUrl('/page-one');
        $this->environment->visitUrl('/page-two');
    }

    public function testVisitPageVisitsTheUrlBuiltByThePage(): void
    {
        $page = mock(PageInterface::class);
        $page->allows()
            ->buildUrl($this->environment)
            ->andReturn('/dashboard');
        $this->automation->allows()
            ->assertTransitionLogEmpty();

        $this->automation->expects()
            ->visitPage('https://my-app.example.com/dashboard')
            ->once();

        $this->environment->visitPage($page);
    }
}
