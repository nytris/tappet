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

namespace Tappet\Core\Automation\Region;

use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Automation\AutomationInterface;

/**
 * Interface RegionAssertionHandlerInterface.
 *
 * Handles region assertions for one or more RegionAssertionInterface implementations.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RegionAssertionHandlerInterface
{
    /**
     * Returns a map of RegionAssertionInterface FQCNs to callable handlers.
     *
     * Each key is the fully qualified class name of a RegionAssertionInterface implementation,
     * and each value is a callable that accepts an instance of that class and performs
     * the corresponding region assertion.
     *
     * @return array<class-string<RegionAssertionInterface>, callable(RegionAssertionInterface, AutomationInterface): void>
     */
    public function getHandlers(): array;
}
