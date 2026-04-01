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

namespace Tappet\Core\Environment;

use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Assertion\StateAssertionInterface;
use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Fixture\ModelProviderInterface;

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
     * Asserts that the current page URL matches the given URL.
     */
    public function assertPage(string $url): void;

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
     * Visits the given page URL.
     */
    public function visitPage(string $url): void;
}
