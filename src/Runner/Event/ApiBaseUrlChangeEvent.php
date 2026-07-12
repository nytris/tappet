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
 * Class ApiBaseUrlChangeEvent.
 *
 * Dispatched when the API base URL has been changed.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ApiBaseUrlChangeEvent implements EventInterface
{
    public function __construct(
        private readonly string $newApiBaseUrl
    ) {
    }

    /**
     * Fetches the new API base URL.
     */
    public function getNewApiBaseUrl(): string
    {
        return $this->newApiBaseUrl;
    }
}
