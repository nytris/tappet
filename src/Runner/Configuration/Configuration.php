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

namespace Tappet\Runner\Configuration;

use Tappet\Common\Event\EventDispatcherInterface;
use Tappet\Runner\Event\ApiBaseUrlChangeEvent;
use Tappet\Runner\Event\BaseUrlChangeEvent;

/**
 * Class Configuration.
 *
 * Handles configuration of the Tappet client.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @param string $apiBaseUrl
     * @param string $baseUrl
     */
    public function __construct(
        private string $apiBaseUrl,
        private string $baseUrl,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

    /**
     * @inheritDoc
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @inheritDoc
     */
    public function setApiBaseUrl(string $apiBaseUrl): void
    {
        $this->apiBaseUrl = $apiBaseUrl;

        $this->eventDispatcher->dispatch(new ApiBaseUrlChangeEvent($apiBaseUrl));
    }

    /**
     * @inheritDoc
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = $baseUrl;

        $this->eventDispatcher->dispatch(new BaseUrlChangeEvent($baseUrl));
    }
}
