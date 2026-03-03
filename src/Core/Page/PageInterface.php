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

namespace Tappet\Core\Page;

use Tappet\Core\Environment\EnvironmentInterface;

interface PageInterface
{
    public function buildUrl(EnvironmentInterface $environment): string;

    public function matchesUrl(string $url): bool;
}
