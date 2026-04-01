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

namespace Tappet\Tests\Functional\Fixtures;

use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Page\PageInterface;

/**
 * Class TestPage.
 *
 * Stub implementation of PageInterface for use in functional tests.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestPage implements PageInterface
{
    public function __construct(private readonly string $url)
    {
    }

    public function buildUrl(EnvironmentInterface $environment): string
    {
        return $this->url;
    }
}
