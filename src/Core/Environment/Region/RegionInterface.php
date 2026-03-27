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

namespace Tappet\Core\Environment\Region;

/**
 * Interface RegionInterface.
 *
 * Represents a region on the page, such as a DOM element that contains a message displayed to the user.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RegionInterface
{
    public function assertContains(string $text): void;

    public function assertDoesNotContain(string $text): void;

    public function getHandle(): string;
}
