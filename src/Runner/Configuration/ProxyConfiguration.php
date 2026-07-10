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

/**
 * Class ProxyConfiguration.
 *
 * Proxies configuration operations to callbacks, for use in RPC setups
 * where the actual state is stored out of process.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ProxyConfiguration implements ConfigurationInterface
{
    /**
     * @param callable(): string $getApiBaseUrlCallback
     * @param callable(): string $getBaseUrlCallback
     * @param callable(string): void $setApiBaseUrlCallback
     * @param callable(string): void $setBaseUrlCallback
     */
    public function __construct(
        private readonly mixed $getApiBaseUrlCallback,
        private readonly mixed $getBaseUrlCallback,
        private readonly mixed $setApiBaseUrlCallback,
        private readonly mixed $setBaseUrlCallback
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getApiBaseUrl(): string
    {
        return ($this->getApiBaseUrlCallback)();
    }

    /**
     * @inheritDoc
     */
    public function getBaseUrl(): string
    {
        return ($this->getBaseUrlCallback)();
    }

    /**
     * @inheritDoc
     */
    public function setApiBaseUrl(string $apiBaseUrl): void
    {
        ($this->setApiBaseUrlCallback)($apiBaseUrl);
    }

    /**
     * @inheritDoc
     */
    public function setBaseUrl(string $baseUrl): void
    {
        ($this->setBaseUrlCallback)($baseUrl);
    }
}
