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

namespace Tappet\Cli\Config;

use Tappet\Cli\Implementation\ImplementationInterface;
use Tappet\Core\Exception\MissingConfigurationException;

/**
 * Interface ConfigInterface.
 *
 * Configuration for Tappet, allowing the Command-Line-Interface implementation
 * and default suite to be configured via `tappet.config.php`.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ConfigInterface
{
    /**
     * Fetches the default API base URL for Tappet, or null if none is configured.
     */
    public function getDefaultApiBaseUrl(): ?string;

    /**
     * Fetches the default API key for Tappet, or null if none is configured.
     */
    public function getDefaultApiKey(): ?string;

    /**
     * Fetches the default GUI application base URL for Tappet, or null if none is configured.
     */
    public function getDefaultBaseUrl(): ?string;

    /**
     * Fetches the default test filter pattern for Tappet, or null if none is configured.
     */
    public function getDefaultFilter(): ?string;

    /**
     * Fetches the default suite name for Tappet, or null if none is configured.
     */
    public function getDefaultSuite(): ?string;

    /**
     * Fetches the Implementation instance, lazily creating the default if not set.
     */
    public function getImplementation(): ImplementationInterface;

    /**
     * Returns true when a `tappet.config.php` was successfully loaded, false when absent.
     */
    public function isPresent(): bool;

    /**
     * Sets the default API base URL for Tappet.
     *
     * @throws MissingConfigurationException When called on a missing config.
     */
    public function setDefaultApiBaseUrl(string $apiBaseUrl): self;

    /**
     * Sets the default API key for Tappet.
     *
     * @throws MissingConfigurationException When called on a missing config.
     */
    public function setDefaultApiKey(string $apiKey): self;

    /**
     * Sets the default GUI application base URL for Tappet.
     *
     * @throws MissingConfigurationException When called on a missing config.
     */
    public function setDefaultBaseUrl(string $baseUrl): self;

    /**
     * Sets the default test filter pattern for Tappet.
     *
     * @throws MissingConfigurationException When called on a missing config.
     */
    public function setDefaultFilter(string $filter): self;

    /**
     * Sets the default suite name for Tappet.
     *
     * @throws MissingConfigurationException When called on a missing config.
     */
    public function setDefaultSuite(string $suiteName): self;

    /**
     * Sets a custom Implementation instance.
     *
     * @throws MissingConfigurationException When called on a missing config.
     */
    public function setImplementation(ImplementationInterface $implementation): self;
}
