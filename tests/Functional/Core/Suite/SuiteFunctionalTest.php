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

namespace Tappet\Tests\Functional\Core\Suite;

use Tappet\Core\Environment\Environment;
use Tappet\Core\Fixture\ModelRepository;
use Tappet\Core\Module\ModuleInterface;
use Tappet\Core\Standard\Action\AssertionAction;
use Tappet\Core\Standard\Action\Enact;
use Tappet\Core\Standard\Action\Type;
use Tappet\Core\Standard\Arrangement\LoadFixture;
use Tappet\Core\Standard\Arrangement\LoadMultipleFixtures;
use Tappet\Core\Standard\Arrangement\OpenPage;
use Tappet\Core\Standard\Assertion\ExpectNewPage;
use Tappet\Core\Standard\Assertion\ExpectRegionContains;
use Tappet\Core\Standard\Assertion\ExpectRegionDoesNotContain;
use Tappet\Core\Standard\Assertion\ExpectState;
use Tappet\Core\Tappet;
use Tappet\Tests\Functional\AbstractFunctionalTestCase;
use Tappet\Tests\Functional\Fixtures\TestAutomation;
use Tappet\Tests\Functional\Fixtures\TestFixture;
use Tappet\Tests\Functional\Fixtures\TestFixtureApi;
use Tappet\Tests\Functional\Fixtures\TestModel;
use Tappet\Tests\Functional\Fixtures\TestPage;

/**
 * Class SuiteFunctionalTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class SuiteFunctionalTest extends AbstractFunctionalTestCase
{
    private TestAutomation $automation;
    private ModuleInterface|null $capturedModule = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = new TestAutomation();

        $modelRepository = new ModelRepository(new \stdClass());
        $environment = new Environment($modelRepository, $this->automation, 'https://example.com');

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

        static::assertSame(
            [['type' => 'visitPage', 'url' => 'https://example.com/login']],
            $this->automation->operations
        );
    }

    public function testSuiteScenarioCanAssertNewPage(): void
    {
        Tappet::describe('my module', [
            Tappet::it('lands on the home page')
                ->assert(new ExpectNewPage(new TestPage('https://example.com/home'))),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertSame(
            [['type' => 'assertPage', 'url' => 'https://example.com/home']],
            $this->automation->operations
        );
    }

    public function testSuiteScenarioCanTypeIntoAField(): void
    {
        $typeAction = new Type('username-field', 'janedoe');

        Tappet::describe('my module', [
            Tappet::it('fills in the username field')
                ->act($typeAction),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performFieldAction', $this->automation->operations[0]['type']);
        static::assertSame($typeAction, $this->automation->operations[0]['action']);
    }

    public function testSuiteScenarioCanPerformAnInteraction(): void
    {
        $action = new Enact('submit-button');

        Tappet::describe('my module', [
            Tappet::it('presses the submit button')
                ->act($action),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performInteraction', $this->automation->operations[0]['type']);
        static::assertSame($action, $this->automation->operations[0]['action']);
    }

    public function testSuiteScenarioCanAssertRegionContains(): void
    {
        $assertion = new ExpectRegionContains('flash-message', 'Saved successfully.');

        Tappet::describe('my module', [
            Tappet::it('sees the flash message')
                ->assert($assertion),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performRegionAssertion', $this->automation->operations[0]['type']);
        static::assertSame($assertion, $this->automation->operations[0]['assertion']);
    }

    public function testSuiteScenarioCanAssertRegionDoesNotContain(): void
    {
        $assertion = new ExpectRegionDoesNotContain('flash-message', 'Something went wrong.');

        Tappet::describe('my module', [
            Tappet::it('does not see an error in the flash message')
                ->assert($assertion),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performRegionAssertion', $this->automation->operations[0]['type']);
        static::assertSame($assertion, $this->automation->operations[0]['assertion']);
    }

    public function testSuiteScenarioCanAssertState(): void
    {
        $assertion = new ExpectState('modal-open');

        Tappet::describe('my module', [
            Tappet::it('sees the modal is open')
                ->assert($assertion),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performStateAssertion', $this->automation->operations[0]['type']);
        static::assertSame($assertion, $this->automation->operations[0]['assertion']);
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

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performStateAssertion', $this->automation->operations[0]['type']);
        static::assertSame($assertion, $this->automation->operations[0]['assertion']);
    }

    public function testSuiteScenarioCanLoadFixture(): void
    {
        $fixtureApi = new TestFixtureApi();
        $modelRepository = new ModelRepository($fixtureApi);
        $environment = new Environment($modelRepository, $this->automation, 'https://example.com');
        Tappet::uninitialise();
        Tappet::initialise(
            function (ModuleInterface $module): void {
                $this->capturedModule = $module;
            },
            $environment
        );

        $fixture = new TestFixture(21);

        Tappet::describe('my module', [
            Tappet::it('loads a fixture')
                ->arrange(new LoadFixture('myHandle', $fixture)),
        ]);

        $this->capturedModule->getScenarios()[0]->perform();

        $model = $modelRepository->getFixtureModel(TestModel::class, 'myHandle');
        static::assertInstanceOf(TestModel::class, $model);
        static::assertSame(serialize($fixture), $model->serialisedFixture);
    }

    public function testSuiteScenarioCanLoadMultipleFixtures(): void
    {
        $fixtureApi = new TestFixtureApi();
        $modelRepository = new ModelRepository($fixtureApi);
        $environment = new Environment($modelRepository, $this->automation, 'https://example.com');
        Tappet::uninitialise();
        Tappet::initialise(
            function (ModuleInterface $module): void {
                $this->capturedModule = $module;
            },
            $environment
        );

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

        static::assertInstanceOf(TestModel::class, $modelRepository->getFixtureModel(TestModel::class, 'firstHandle'));
        static::assertSame(serialize($firstFixture), $modelRepository->getFixtureModel(TestModel::class, 'firstHandle')->serialisedFixture);
        static::assertInstanceOf(TestModel::class, $modelRepository->getFixtureModel(TestModel::class, 'secondHandle'));
        static::assertSame(serialize($secondFixture), $modelRepository->getFixtureModel(TestModel::class, 'secondHandle')->serialisedFixture);
    }
}
