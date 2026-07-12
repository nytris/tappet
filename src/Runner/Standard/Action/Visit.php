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

namespace Tappet\Runner\Standard\Action;

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Page\PageInterface;

/**
 * Class Visit.
 *
 * Navigates the browser to a new page during the act stage, updating the expected current page.
 * Use OpenPage in the arrangement stage instead when navigating as part of test setup.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Visit implements ActionInterface
{
    public function __construct(
        private readonly PageInterface $page
    ) {
    }

    /**
     * Fetches the page to navigate to.
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
        $environment->visitPage($this->page);
    }
}
