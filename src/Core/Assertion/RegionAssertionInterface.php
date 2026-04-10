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

namespace Tappet\Core\Assertion;

/**
 * Interface RegionAssertionInterface.
 *
 * Represents an assertion performed on a region of the page.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RegionAssertionInterface extends AssertionInterface
{
    /**
     * Fetches the unique handle of the region on which the assertion will be performed.
     */
    public function getRegionHandle(): string;
}
