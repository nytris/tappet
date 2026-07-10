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

namespace Tappet\Tests\Functional\Core\Module;

use Tappet\Common\Event\EventDispatcher;
use Tappet\Runner\Client\Client;
use Tappet\Runner\Configuration\Configuration;
use Tappet\Runner\Environment\Environment;
use Tappet\Runner\Event\ApiBaseUrlChangeEvent;
use Tappet\Runner\Fixture\ModelRepository;
use Tappet\Runner\Module\ModuleInterface;
use Tappet\Runner\Standard\Action\AssertionAction;
use Tappet\Runner\Standard\Action\Enact;
use Tappet\Runner\Standard\Action\Type;
use Tappet\Runner\Standard\Action\Visit;
use Tappet\Runner\Standard\Arrangement\LoadFixture;
use Tappet\Runner\Standard\Arrangement\LoadMultipleFixtures;
use Tappet\Runner\Standard\Arrangement\OpenPage;
use Tappet\Runner\Standard\Assertion\ExpectNewPage;
use Tappet\Runner\Standard\Assertion\ExpectRegionContains;
use Tappet\Runner\Standard\Assertion\ExpectRegionDoesNotContain;
use Tappet\Runner\Standard\Assertion\ExpectState;
use Tappet\Runner\Tappet;
use Tappet\Runner\Transition\NavigationTransition;
use Tappet\Runner\Transition\PageTransition;
use Tappet\Tests\Functional\AbstractFunctionalTestCase;
use Tappet\Tests\Functional\Fixtures\TestAutomation;
use Tappet\Tests\Functional\Fixtures\TestFixture;
use Tappet\Tests\Functional\Fixtures\TestFixtureApi;
use Tappet\Tests\Functional\Fixtures\TestModel;
use Tappet\Tests\Functional\Fixtures\TestPage;

/**
 * Class ModuleFunctionalTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ModuleFunctionalTest extends AbstractFunctionalTestCase
{
    private TestAutomation $automation;
    private ModuleInterface|null $capturedModule = null;
    private ModelRepository $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = new TestAutomation();

        $fixtureApi = new TestFixtureApi();

        $eventDispatcher = new EventDispatcher();
        $configuration = new Configuration(
            'https://my-api.example.com/my-api',
            'https://my-app.example.com',
            $eventDispatcher
        );

        $eventDispatcher->addEventListener(
            ApiBaseUrlChangeEvent::class,
            static function (ApiBaseUrlChangeEvent $event) use ($configuration): void {
                $configuration->setApiBaseUrl($event->getNewApiBaseUrl());
            }
        );

        $client = new Client($eventDispatcher, $configuration, $fixtureApi);
        $this->modelRepository = new ModelRepository($client);

        $environment = new Environment($this->modelRepository, $this->automation, $configuration);

        Tappet::initialise(
            function (ModuleInterface $module): void {
                $this->capturedModule = $module;
            },
            $environment
        );
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Tappet::uninitialise();
    }

    public function testSuiteScenarioCanOpenPage(): void
    {
        Tappet::describe('my module', [
            Tappet::it('visits the login page')
                ->arrange(new OpenPage(new TestPage('https://example.com/login'))),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        // ->visitPage() always checks the transition log is empty (no prior navigation) before visiting.
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('visitPage', $this->automation->operations[1]['type']);
        static::assertSame('https://example.com/login', $this->automation->operations[1]['url']);
    }

    public function testSuiteScenarioCanAssertNewPage(): void
    {
        Tappet::describe('my module', [
            Tappet::it('lands on the home page')
                ->assert(new ExpectNewPage(new TestPage('https://example.com/home'))),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('waitForTransition', $this->automation->operations[0]['type']);
        static::assertInstanceOf(PageTransition::class, $this->automation->operations[0]['transition']);
        static::assertSame('https://example.com/home', $this->automation->operations[0]['transition']->getUrl());
    }

    public function testSuiteScenarioCanTypeIntoAField(): void
    {
        $typeAction = new Type('username-field', 'janedoe');

        Tappet::describe('my module', [
            Tappet::it('fills in the username field')
                ->act($typeAction),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        // No current transition - transition log emptiness is checked before the field action.
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('performFieldAction', $this->automation->operations[1]['type']);
        static::assertSame($typeAction, $this->automation->operations[1]['action']);
    }

    public function testSuiteScenarioCanPerformAnInteraction(): void
    {
        $action = new Enact('submit-button');

        Tappet::describe('my module', [
            Tappet::it('presses the submit button')
                ->act($action),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('performInteraction', $this->automation->operations[1]['type']);
        static::assertSame($action, $this->automation->operations[1]['action']);
    }

    public function testSuiteScenarioCanAssertRegionContains(): void
    {
        $assertion = new ExpectRegionContains('flash-message', 'Saved successfully.');

        Tappet::describe('my module', [
            Tappet::it('sees the flash message')
                ->assert($assertion),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('performRegionAssertion', $this->automation->operations[1]['type']);
        static::assertSame($assertion, $this->automation->operations[1]['assertion']);
    }

    public function testSuiteScenarioCanAssertRegionDoesNotContain(): void
    {
        $assertion = new ExpectRegionDoesNotContain('flash-message', 'Something went wrong.');

        Tappet::describe('my module', [
            Tappet::it('does not see an error in the flash message')
                ->assert($assertion),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('performRegionAssertion', $this->automation->operations[1]['type']);
        static::assertSame($assertion, $this->automation->operations[1]['assertion']);
    }

    public function testSuiteScenarioCanAssertState(): void
    {
        $assertion = new ExpectState('modal-open');

        Tappet::describe('my module', [
            Tappet::it('sees the modal is open')
                ->assert($assertion),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('performStateAssertion', $this->automation->operations[1]['type']);
        static::assertSame($assertion, $this->automation->operations[1]['assertion']);
    }

    public function testSuiteScenarioCanPerformAssertionDuringActStage(): void
    {
        $assertion = new ExpectState('form-submitted');
        $action = new AssertionAction($assertion);

        Tappet::describe('my module', [
            Tappet::it('checks state during the act stage')
                ->act($action),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('performStateAssertion', $this->automation->operations[1]['type']);
        static::assertSame($assertion, $this->automation->operations[1]['assertion']);
    }

    public function testSuiteScenarioCanLoadFixture(): void
    {
        $fixture = new TestFixture(21);

        Tappet::describe('my module', [
            Tappet::it('loads a fixture')
                ->arrange(new LoadFixture('myHandle', $fixture)),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        $model = $this->modelRepository->getFixtureModel(TestModel::class, 'myHandle');
        static::assertInstanceOf(TestModel::class, $model);
        static::assertSame(serialize($fixture), $model->serialisedFixture);
    }

    public function testSuiteScenarioCanVisitPageDuringActStage(): void
    {
        Tappet::describe('my module', [
            Tappet::it('visits a page during act')
                ->act(new Visit(new TestPage('https://example.com/some-page'))),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('visitPage', $this->automation->operations[1]['type']);
        static::assertSame('https://example.com/some-page', $this->automation->operations[1]['url']);
    }

    public function testSuiteScenarioChecksForUnexpectedTransitionBeforeFieldActionWhenPageHasBeenSet(): void
    {
        $typeAction = new Type('username-field', 'janedoe');

        Tappet::describe('my module', [
            Tappet::it('fills in the username field after visiting')
                ->arrange(new OpenPage(new TestPage('https://example.com/login')))
                ->act($typeAction),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(4, $this->automation->operations);
        // Arrange: log emptiness check then the page visit.
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('visitPage', $this->automation->operations[1]['type']);
        static::assertSame('https://example.com/login', $this->automation->operations[1]['url']);
        // Before act step: wait for the expected transition (verifies we're on the expected page).
        static::assertSame('waitForTransition', $this->automation->operations[2]['type']);
        static::assertInstanceOf(NavigationTransition::class, $this->automation->operations[2]['transition']);
        static::assertSame('https://example.com/login', $this->automation->operations[2]['transition']->getUrl());
        // Act: the field action.
        static::assertSame('performFieldAction', $this->automation->operations[3]['type']);
        static::assertSame($typeAction, $this->automation->operations[3]['action']);
    }

    public function testSuiteScenarioChecksTransitionLogEmptyBeforeFieldActionWhenNoPageHasBeenSet(): void
    {
        $typeAction = new Type('username-field', 'janedoe');

        Tappet::describe('my module', [
            Tappet::it('fills in a field without a prior page visit')
                ->act($typeAction),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(2, $this->automation->operations);
        // No current transition - log emptiness is checked before the action.
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('performFieldAction', $this->automation->operations[1]['type']);
    }

    public function testSuiteScenarioChecksForUnexpectedTransitionBeforeInteractionWhenPageHasBeenSet(): void
    {
        $enactAction = new Enact('submit-button');

        Tappet::describe('my module', [
            Tappet::it('clicks a button after visiting')
                ->arrange(new OpenPage(new TestPage('https://example.com/form')))
                ->act($enactAction),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(4, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('visitPage', $this->automation->operations[1]['type']);
        // Before the interaction: wait for the expected transition.
        static::assertSame('waitForTransition', $this->automation->operations[2]['type']);
        static::assertInstanceOf(NavigationTransition::class, $this->automation->operations[2]['transition']);
        static::assertSame('https://example.com/form', $this->automation->operations[2]['transition']->getUrl());
        static::assertSame('performInteraction', $this->automation->operations[3]['type']);
    }

    public function testSuiteScenarioChecksForUnexpectedTransitionBeforeRegionAssertionWhenPageHasBeenSet(): void
    {
        $assertion = new ExpectRegionContains('flash', 'Saved.');

        Tappet::describe('my module', [
            Tappet::it('checks a region after visiting')
                ->arrange(new OpenPage(new TestPage('https://example.com/list')))
                ->assert($assertion),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(4, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('visitPage', $this->automation->operations[1]['type']);
        // Before the region assertion: wait for the expected transition.
        static::assertSame('waitForTransition', $this->automation->operations[2]['type']);
        static::assertInstanceOf(NavigationTransition::class, $this->automation->operations[2]['transition']);
        static::assertSame('https://example.com/list', $this->automation->operations[2]['transition']->getUrl());
        static::assertSame('performRegionAssertion', $this->automation->operations[3]['type']);
    }

    public function testSuiteScenarioWaitsForDeclaredTransitionWhenExpectNewPageIsAsserted(): void
    {
        $regionAssertion = new ExpectRegionContains('flash', 'Saved.');

        Tappet::describe('my module', [
            Tappet::it('lands on a new page then checks a region')
                ->arrange(new OpenPage(new TestPage('https://example.com/form')))
                ->assert(
                    new ExpectNewPage(new TestPage('https://example.com/list')),
                    $regionAssertion
                ),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(5, $this->automation->operations);
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[0]['type']);
        static::assertSame('visitPage', $this->automation->operations[1]['type']);
        static::assertSame('https://example.com/form', $this->automation->operations[1]['url']);
        // ExpectNewPage waits for the declared page transition immediately, clearing whatever
        // pending transition was left by the earlier visitPage() (for /form) in the process.
        static::assertSame('waitForTransition', $this->automation->operations[2]['type']);
        static::assertInstanceOf(PageTransition::class, $this->automation->operations[2]['transition']);
        static::assertSame('https://example.com/list', $this->automation->operations[2]['transition']->getUrl());
        // No transition is pending any more, so the region assertion just checks the log is empty.
        static::assertSame('assertTransitionLogEmpty', $this->automation->operations[3]['type']);
        static::assertSame('performRegionAssertion', $this->automation->operations[4]['type']);
    }

    public function testSuiteScenarioCanLoadMultipleFixtures(): void
    {
        $firstFixture = new TestFixture(21);
        $secondFixture = new TestFixture(42);

        Tappet::describe('my module', [
            Tappet::it('loads multiple fixtures')
                ->arrange(new LoadMultipleFixtures([
                    'firstHandle' => $firstFixture,
                    'secondHandle' => $secondFixture,
                ])),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertInstanceOf(TestModel::class, $this->modelRepository->getFixtureModel(TestModel::class, 'firstHandle'));
        static::assertSame(serialize($firstFixture), $this->modelRepository->getFixtureModel(TestModel::class, 'firstHandle')->serialisedFixture);
        static::assertInstanceOf(TestModel::class, $this->modelRepository->getFixtureModel(TestModel::class, 'secondHandle'));
        static::assertSame(serialize($secondFixture), $this->modelRepository->getFixtureModel(TestModel::class, 'secondHandle')->serialisedFixture);
    }
}
