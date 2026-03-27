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

use Tappet\Core\Automation\AutomationInterface;

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
     * @var array<array{type: string, ...}>
     */
    public array $operations = [];

    public function assertPage(string $url): void
    {
        $this->operations[] = ['type' => 'assertPage', 'url' => $url];
    }

    public function assertRegionContains(string $handle, string $text): void
    {
        $this->operations[] = ['type' => 'assertRegionContains', 'handle' => $handle, 'text' => $text];
    }

    public function assertRegionDoesNotContain(string $handle, string $text): void
    {
        $this->operations[] = ['type' => 'assertRegionDoesNotContain', 'handle' => $handle, 'text' => $text];
    }

    public function performInteraction(string $handle): void
    {
        $this->operations[] = ['type' => 'performInteraction', 'handle' => $handle];
    }

    public function typeField(string $fieldHandle, string $text): void
    {
        $this->operations[] = ['type' => 'typeField', 'fieldHandle' => $fieldHandle, 'text' => $text];
    }

    public function visitPage(string $url): void
    {
        $this->operations[] = ['type' => 'visitPage', 'url' => $url];
    }
}
