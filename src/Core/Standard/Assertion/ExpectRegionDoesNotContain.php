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

namespace Tappet\Core\Standard\Assertion;

use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

class ExpectRegionDoesNotContain implements AssertionInterface
{
    /**
     * @var string
     */
    private $regionHandle;
    /**
     * @var string
     */
    private $text;

    public function __construct(string $regionHandle, string $text)
    {
        $this->regionHandle = $regionHandle;
        $this->text = $text;
    }

    public function getRegionHandle(): string
    {
        return $this->regionHandle;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        $environment->getRegion($this->regionHandle)->assertDoesNotContain($this->text);
    }
}
