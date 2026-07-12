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

namespace Tappet\Runner\Standard\Assertion;

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Page\PageInterface;
use Tappet\Runner\Transition\PageTransition;

/**
 * Class ExpectNewPage.
 *
 * Declares that the next logged transition must be a navigation to the given page's URL.
 * May be used in any of the arrange, act, or assert stages of a scenario.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectNewPage implements ArrangementInterface, ActionInterface, AssertionInterface
{
    public function __construct(
        private readonly PageInterface $page
    ) {
    }

    /**
     * Fetches the page expected to be loaded.
     */
    public function getPage(): PageInterface
    {
        return $this->page;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->assertTransition(new PageTransition($this->page, $environment));
    }
}
