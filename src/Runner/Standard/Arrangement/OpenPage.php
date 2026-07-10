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

namespace Tappet\Runner\Standard\Arrangement;

use Tappet\Runner\Arrangement\AbstractArrangement;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Page\PageInterface;

class OpenPage extends AbstractArrangement
{
    public function __construct(
        private readonly PageInterface $page
    ) {
    }

    public function getPage(): PageInterface
    {
        return $this->page;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        $environment->visitPage($this->page);
    }
}
