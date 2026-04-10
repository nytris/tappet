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

/**
 * Class Config.
 *
 * Configuration for Tappet, allowing the Command-Line-Interface implementation
 * and default suite to be configured via `tappet.config.php`.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Config implements ConfigInterface
{
    private ?string $defaultSuiteName = null;
    private ?ImplementationInterface $implementation = null;

    /**
     * @inheritDoc
     */
    public function getDefaultSuite(): ?string
    {
        return $this->defaultSuiteName;
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
        return true;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultSuite(string $suiteName): ConfigInterface
    {
        $this->defaultSuiteName = $suiteName;

        return $this; // Fluent interface.
    }

    /**
     * @inheritDoc
     */
    public function setImplementation(ImplementationInterface $implementation): ConfigInterface
    {
        $this->implementation = $implementation;

        return $this; // Fluent interface.
    }
}
