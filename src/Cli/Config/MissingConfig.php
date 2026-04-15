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

use Tappet\Cli\Implementation\DefaultImplementation;
use Tappet\Cli\Implementation\ImplementationInterface;
use Tappet\Core\Exception\MissingConfigurationException;

/**
 * Class MissingConfig.
 *
 * Represents the absence of a `tappet.config.php` file. Allows global commands such as
 * `help` and `version` to function without a config file, while RunCommand detects
 * the absence via `->isPresent()` and reports an appropriate error before attempting
 * any suite-specific operations.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MissingConfig implements ConfigInterface
{
    private ?ImplementationInterface $implementation = null;

    /**
     * @inheritDoc
     */
    public function getDefaultApiBaseUrl(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultApiKey(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultBaseUrl(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultFilter(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultSuite(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getImplementation(): ImplementationInterface
    {
        if ($this->implementation === null) {
            $this->implementation = new DefaultImplementation($this);
        }

        return $this->implementation;
    }

    /**
     * @inheritDoc
     */
    public function isPresent(): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultApiBaseUrl(string $apiBaseUrl): ConfigInterface
    {
        throw new MissingConfigurationException(
            'Cannot set default API base URL for a MissingConfig - did you mean to use Config?'
        );
    }

    /**
     * @inheritDoc
     */
    public function setDefaultApiKey(string $apiKey): ConfigInterface
    {
        throw new MissingConfigurationException(
            'Cannot set default API key for a MissingConfig - did you mean to use Config?'
        );
    }

    /**
     * @inheritDoc
     */
    public function setDefaultBaseUrl(string $baseUrl): ConfigInterface
    {
        throw new MissingConfigurationException(
            'Cannot set default base URL for a MissingConfig - did you mean to use Config?'
        );
    }

    /**
     * @inheritDoc
     */
    public function setDefaultFilter(string $filter): ConfigInterface
    {
        throw new MissingConfigurationException(
            'Cannot set default filter for a MissingConfig - did you mean to use Config?'
        );
    }

    /**
     * @inheritDoc
     */
    public function setDefaultSuite(string $suiteName): ConfigInterface
    {
        throw new MissingConfigurationException(
            'Cannot set default suite for a MissingConfig - did you mean to use Config?'
        );
    }

    /**
     * @inheritDoc
     */
    public function setImplementation(ImplementationInterface $implementation): ConfigInterface
    {
        throw new MissingConfigurationException(
            'Cannot set implementation for a MissingConfig - did you mean to use Config?'
        );
    }
}
