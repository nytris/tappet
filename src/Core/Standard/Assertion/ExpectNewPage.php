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
use Tappet\Core\Page\PageInterface;

class ExpectNewPage implements AssertionInterface
{
    /**
     * @var PageInterface
     */
    private $page;

    public function __construct(PageInterface $page)
    {
        $this->page = $page;
    }

    public function getPage(): PageInterface
    {
        return $this->page;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        $environment->assertPage($this->page->buildUrl($environment));
    }
}
