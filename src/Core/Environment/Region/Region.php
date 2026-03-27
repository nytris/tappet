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

use Tappet\Core\Automation\AutomationInterface;

/**
 * Class Region.
 *
 * Represents a region on the page, such as a DOM element that contains a message displayed to the user.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Region implements RegionInterface
{
    /**
     * @var AutomationInterface
     */
    private $automation;
    /**
     * @var string
     */
    private $handle;

    public function __construct(AutomationInterface $automation, string $handle)
    {
        $this->automation = $automation;
        $this->handle = $handle;
    }

    public function assertContains(string $text): void
    {
        $this->automation->assertRegionContains($this->handle, $text);
    }

    public function assertDoesNotContain(string $text): void
    {
        $this->automation->assertRegionDoesNotContain($this->handle, $text);
    }

    public function getHandle(): string
    {
        return $this->handle;
    }
}
