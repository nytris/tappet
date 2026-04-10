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
 * Interface RegionAssertionRegistryInterface.
 *
 * Maps region assertion types to their handlers and dispatches region assertions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RegionAssertionRegistryInterface
{
    /**
     * Dispatches the given region assertion to the handler registered for the given region type.
     */
    public function handleRegionAssertion(
        string $regionType,
        RegionAssertionInterface $assertion,
        AutomationInterface $automation
    ): void;

    /**
     * Registers a handler for the given region type.
     */
    public function registerRegionAssertionHandler(string $regionType, RegionAssertionHandlerInterface $handler): void;
}
