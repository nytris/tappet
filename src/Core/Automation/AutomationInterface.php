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

interface AutomationInterface
{
    public function assertPage(string $url): void;

    public function assertRegionContains(string $handle, string $text): void;

    public function assertRegionDoesNotContain(string $handle, string $text): void;

    public function performInteraction(string $handle): void;

    public function typeField(string $fieldHandle, string $text): void;

    public function visitPage(string $url): void;
}
