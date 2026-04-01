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

namespace Tappet\Core\Automation;

use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Assertion\StateAssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

/**
 * Interface AutomationInterface.
 *
 * Represents the automation layer of a scenario, e.g. the integration with Cypress.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface AutomationInterface
{
    /**
     * Asserts that the current page URL matches the given URL.
     */
    public function assertPage(string $url, EnvironmentInterface $environment): void;

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
     */
    public function performRegionAssertion(RegionAssertionInterface $assertion): void;

    /**
     * Performs the given state assertion.
     */
    public function performStateAssertion(StateAssertionInterface $assertion): void;

    /**
     * Visits the given page URL.
     */
    public function visitPage(string $url): void;
}
