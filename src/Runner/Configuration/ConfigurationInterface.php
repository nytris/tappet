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
 * Interface ConfigurationInterface.
 *
 * Handles configuration of the Tappet client.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ConfigurationInterface
{
    /**
     * Fetches the base URL of the API.
     */
    public function getApiBaseUrl(): string;

    /**
     * Fetches the base URL of the application under test.
     */
    public function getBaseUrl(): string;

    /**
     * Sets the base URL of the API.
     */
    public function setApiBaseUrl(string $apiBaseUrl): void;

    /**
     * Sets the base URL of the application under test.
     */
    public function setBaseUrl(string $baseUrl): void;
}
