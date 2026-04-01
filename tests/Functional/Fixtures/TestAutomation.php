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

use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Assertion\StateAssertionInterface;
use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Environment\EnvironmentInterface;

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

    public function assertPage(string $url, EnvironmentInterface $environment): void
    {
        $this->operations[] = ['type' => 'assertPage', 'url' => $url];
    }

    public function performFieldAction(FieldActionInterface $action): void
    {
        $this->operations[] = ['type' => 'performFieldAction', 'action' => $action];
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

    public function visitPage(string $url): void
    {
        $this->operations[] = ['type' => 'visitPage', 'url' => $url];
    }
}
