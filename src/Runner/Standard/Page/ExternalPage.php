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
 * Class ExternalPage.
 *
 * Defines a generic page that lies outside the application.
 * For within-application URLs (and external pages if applicable),
 * define a relevant PageInterface implementation.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExternalPage implements PageInterface
{
    public function __construct(
        private readonly string $url
    ) {
    }

    /**
     * @inheritDoc
     */
    public function buildUrl(EnvironmentInterface $environment): string
    {
        return $this->url;
    }
}
