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

namespace Tappet\Runner\Page;

use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Interface PageInterface.
 *
 * Represents a page that may be navigated to.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface PageInterface
{
    /**
     * Builds the URL to the page. May return a relative URL,
     * in which case it will be made fully-qualified using the current base URL.
     */
    public function buildUrl(EnvironmentInterface $environment): string;
}
