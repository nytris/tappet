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

namespace Tappet\Tests\Functional\Fixtures;

use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Assertion\RegionAssertionInterface;
use Tappet\Runner\Assertion\StateAssertionInterface;
use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Runner\Transition\TransitionInterface;

/**
 * Class TestAutomation.
 *
 * Stub implementation of AutomationInterface for use in functional tests.
 * Concrete implementations live outside this library, such as in tappet/cypress.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestAutomation implements AutomationInterface
{
    /**
     * @var array<array<string, mixed>>
     */
    public array $operations = [];

    public function assertTransitionLogEmpty(): void
    {
        $this->operations[] = ['type' => 'assertTransitionLogEmpty'];
    }

    public function checkForUnexpectedTransition(TransitionInterface $transition): void
    {
        $this->operations[] = ['type' => 'checkForUnexpectedTransition', 'transition' => $transition];
    }

    public function performFieldAction(FieldActionInterface $action): void
    {
        $this->operations[] = ['type' => 'performFieldAction', 'action' => $action];
    }

    public function performFieldAssertion(FieldAssertionInterface $assertion): void
    {
        $this->operations[] = ['type' => 'performFieldAssertion', 'assertion' => $assertion];
    }

    public function performInteraction(InteractionInterface $interaction): void
    {
        $this->operations[] = ['type' => 'performInteraction', 'action' => $interaction];
    }

    public function performRegionAssertion(RegionAssertionInterface $assertion): void
    {
        $this->operations[] = ['type' => 'performRegionAssertion', 'assertion' => $assertion];
    }

    public function performStateAssertion(StateAssertionInterface $assertion): void
    {
        $this->operations[] = ['type' => 'performStateAssertion', 'assertion' => $assertion];
    }

    public function pushTransition(TransitionInterface $transition): void
    {
        $this->operations[] = ['type' => 'pushTransition', 'transition' => $transition];
    }

    public function visitPage(string $url): void
    {
        $this->operations[] = ['type' => 'visitPage', 'url' => $url];
    }

    public function waitForTransition(TransitionInterface $transition): void
    {
        $this->operations[] = ['type' => 'waitForTransition', 'transition' => $transition];
    }
}
