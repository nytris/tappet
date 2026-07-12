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

namespace Tappet\Runner\Event;

use Tappet\Common\Event\EventInterface;

/**
 * Class BaseUrlChangeEvent.
 *
 * Dispatched when the base URL of the application under test has been changed.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class BaseUrlChangeEvent implements EventInterface
{
    public function __construct(
        private readonly string $newBaseUrl
    ) {
    }

    /**
     * Fetches the new base URL.
     */
    public function getNewBaseUrl(): string
    {
        return $this->newBaseUrl;
    }
}
