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
use Tappet\Core\Standard\Action\PerformInteraction;
use Tappet\Core\Standard\Action\Type;
use Tappet\Core\Standard\Arrangement\OpenPage;
use Tappet\Core\Standard\Assertion\ExpectNewPage;
use Tappet\Core\Standard\Assertion\ExpectRegionContains;
use Tappet\Core\Standard\Assertion\ExpectRegionDoesNotContain;
use Tappet\Tests\Functional\Fixtures\TestPage;
use Tappet\Core\Suite\SuiteInterface;
use Tappet\Core\Tappet;
use Tappet\Tests\Functional\AbstractFunctionalTestCase;
use Tappet\Tests\Functional\Fixtures\TestAutomation;

/**
 * Class SuiteFunctionalTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class SuiteFunctionalTest extends AbstractFunctionalTestCase
{
    private TestAutomation $automation;
    private SuiteInterface|null $capturedSuite = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = new TestAutomation();

        $modelRepository = new ModelRepository(new \stdClass());
        $environment = new Environment($modelRepository, $this->automation, 'https://example.com');

        Tappet::initialise(
            function (SuiteInterface $suite): void {
                $this->capturedSuite = $suite;
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
        Tappet::describe('my suite', [
            Tappet::it('visits the login page')
                ->arrange(new OpenPage(new TestPage('https://example.com/login'))),
        ]);

        $this->capturedSuite->getScenarios()[0]->perform();

        static::assertSame(
            [['type' => 'visitPage', 'url' => 'https://example.com/login']],
            $this->automation->operations
        );
    }

    public function testSuiteScenarioCanAssertNewPage(): void
    {
        Tappet::describe('my suite', [
            Tappet::it('lands on the home page')
                ->assert(new ExpectNewPage(new TestPage('https://example.com/home'))),
        ]);

        $this->capturedSuite->getScenarios()[0]->perform();

        static::assertSame(
            [['type' => 'assertPage', 'url' => 'https://example.com/home']],
            $this->automation->operations
        );
    }

    public function testSuiteScenarioCanTypeIntoAField(): void
    {
        $typeAction = new Type('username-field', 'janedoe');

        Tappet::describe('my suite', [
            Tappet::it('fills in the username field')
                ->act($typeAction),
        ]);

        $this->capturedSuite->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performFieldAction', $this->automation->operations[0]['type']);
        static::assertSame($typeAction, $this->automation->operations[0]['action']);
    }

    public function testSuiteScenarioCanPerformAnInteraction(): void
    {
        $action = new PerformInteraction('submit-button');

        Tappet::describe('my suite', [
            Tappet::it('presses the submit button')
                ->act($action),
        ]);

        $this->capturedSuite->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performInteraction', $this->automation->operations[0]['type']);
        static::assertSame($action, $this->automation->operations[0]['action']);
    }

    public function testSuiteScenarioCanAssertRegionContains(): void
    {
        $assertion = new ExpectRegionContains('flash-message', 'Saved successfully.');

        Tappet::describe('my suite', [
            Tappet::it('sees the flash message')
                ->assert($assertion),
        ]);

        $this->capturedSuite->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performRegionAssertion', $this->automation->operations[0]['type']);
        static::assertSame($assertion, $this->automation->operations[0]['assertion']);
    }

    public function testSuiteScenarioCanAssertRegionDoesNotContain(): void
    {
        $assertion = new ExpectRegionDoesNotContain('flash-message', 'Something went wrong.');

        Tappet::describe('my suite', [
            Tappet::it('does not see an error in the flash message')
                ->assert($assertion),
        ]);

        $this->capturedSuite->getScenarios()[0]->perform();

        static::assertCount(1, $this->automation->operations);
        static::assertSame('performRegionAssertion', $this->automation->operations[0]['type']);
        static::assertSame($assertion, $this->automation->operations[0]['assertion']);
    }
}
