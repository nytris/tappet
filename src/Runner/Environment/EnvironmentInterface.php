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

namespace Tappet\Runner\Environment;

use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Assertion\RegionAssertionInterface;
use Tappet\Runner\Assertion\StateAssertionInterface;
use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Runner\Fixture\ModelProviderInterface;
use Tappet\Runner\Page\PageInterface;
use Tappet\Runner\Transition\TransitionInterface;

/**
 * Interface EnvironmentInterface.
 *
 * Represents the test environment provided to test components.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface EnvironmentInterface extends ModelProviderInterface
{
    /**
     * Asserts that the expected transition is the next logged one, waiting if needed.
     * Also updates the expected current transition for subsequent implicit checks.
     */
    public function assertTransition(TransitionInterface $transition): void;

    /**
     * Asserts that the transition log has no remaining unconsumed entries.
     * Called at the end of a scenario to catch any unexpected transitions that occurred
     * after the last explicit assertion.
     */
    public function assertTransitionLogEmpty(): void;

    /**
     * Makes the given URL fully-qualified, relative to the current base URL.
     */
    public function buildFullyQualifiedUrl(string $url): string;

    /**
     * Builds the fully-qualified URL to a page.
     */
    public function buildPageUrl(PageInterface $page): string;

    /**
     * Fetches the underlying automation layer abstraction.
     */
    public function getAutomation(): AutomationInterface;

    /**
     * Fetches the current base URL of the application under test.
     */
    public function getBaseUrl(): string;

    /**
     * Loads the given fixture.
     *
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): void;

    /**
     * Loads multiple fixtures at once.
     *
     * @param array<string, FixtureInterface<ModelInterface>> $fixtures
     */
    public function loadMultipleFixtures(array $fixtures): void;

    /**
     * Performs the given field action.
     */
    public function performFieldAction(FieldActionInterface $action): void;

    /**
     * Performs the given field assertion.
     * For example, asserting that a field has a specific value.
     */
    public function performFieldAssertion(FieldAssertionInterface $assertion): void;

    /**
     * Performs the given interaction, e.g. clicking a button.
     */
    public function performInteraction(InteractionInterface $interaction): void;

    /**
     * Performs the given region assertion.
     * For example, asserting that a given flash message is displayed.
     */
    public function performRegionAssertion(RegionAssertionInterface $assertion): void;

    /**
     * Performs the given state assertion.
     * For example, asserting that a given component is visible on the page.
     */
    public function performStateAssertion(StateAssertionInterface $assertion): void;

    /**
     * Visits the given page, logging the navigation as the pending expected transition.
     * If a previous transition is pending, it is first checked via the automation layer.
     *
     * If the server redirects to a different URL, provide an ExpectNewPage assertion as
     * the very next step to declare the expected final URL.
     */
    public function visitPage(PageInterface $page): void;

    /**
     * Visits the given URL, logging the navigation as the pending expected transition.
     * If a previous transition is pending, it is first checked via the automation layer.
     *
     * If the server redirects to a different URL, provide an ExpectNewPage assertion as
     * the very next step to declare the expected final URL.
     *
     * @param string $url The URL to navigate to. May be relative (will be prefixed with base URL).
     */
    public function visitUrl(string $url): void;
}
