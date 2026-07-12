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

namespace Tappet\Runner\Automation;

use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Assertion\RegionAssertionInterface;
use Tappet\Runner\Assertion\StateAssertionInterface;
use Tappet\Runner\Exception\TransitionLogNotEmptyException;
use Tappet\Runner\Exception\TransitionWaitTimeoutException;
use Tappet\Runner\Exception\UnexpectedTransitionException;
use Tappet\Runner\Transition\TransitionInterface;

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
     * Asserts that the transition log has no remaining unconsumed entries.
     * Called at the end of each scenario to detect unexpected trailing transitions.
     *
     * @throws TransitionLogNotEmptyException
     */
    public function assertTransitionLogEmpty(): void;

    /**
     * Checks the transition log for unexpected entries since the last check.
     *
     * - If the log has no unconsumed entries, passes immediately (no new transitions).
     * - If the next unconsumed log entry matches the expected transition, consumes it and passes.
     * - If the next unconsumed log entry does not match, fails immediately with the full log.
     *
     * Called before every non-declaring step to detect unexpected whole-page-state changes.
     *
     * @throws UnexpectedTransitionException
     */
    public function checkForUnexpectedTransition(TransitionInterface $transition): void;

    /**
     * Performs the given field action.
     */
    public function performFieldAction(FieldActionInterface $action): void;

    /**
     * Performs the given field assertion.
     */
    public function performFieldAssertion(FieldAssertionInterface $assertion): void;

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
     * Pushes a transition to the log.
     *
     * Called by plugins (such as a MutationObserver handler) to record a state
     * change such as a modal opening or closing.
     */
    public function pushTransition(TransitionInterface $transition): void;

    /**
     * Visits the given page URL.
     */
    public function visitPage(string $url): void;

    /**
     * Waits for the expected transition to appear as the next unconsumed log entry.
     *
     * - If the next unconsumed log entry matches the expected transition, consumes it and passes.
     * - If there are no unconsumed entries yet, waits (using the automation layer's retry mechanism).
     * - If the next unconsumed log entry does not match, fails immediately with the full log.
     *
     * Used by explicit assertions such as ExpectNewPage and ExpectTransition.
     *
     * @throws TransitionWaitTimeoutException
     */
    public function waitForTransition(TransitionInterface $transition): void;
}
