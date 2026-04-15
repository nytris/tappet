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

use Tappet\Core\Exception\InvalidConfigurationException;

/**
 * Class ConfigResolver.
 *
 * Resolves the config from `tappet.config.php`.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ConfigResolver implements ConfigResolverInterface
{
    public function __construct(
        private readonly string $projectRoot,
        private readonly string $configFileName = 'tappet.config.php',
        private readonly string $localConfigFileName = 'tappet.config.local.php'
    ) {
    }

    /**
     * @inheritDoc
     */
    public function resolveConfig(): ConfigInterface
    {
        // Check the local config file first, falling back to the standard config file.
        $localConfigPath = $this->projectRoot . DIRECTORY_SEPARATOR . $this->localConfigFileName;
        $configPath = $this->projectRoot . DIRECTORY_SEPARATOR . $this->configFileName;

        if (is_file($localConfigPath)) {
            $resolvedPath = $localConfigPath;
        } elseif (is_file($configPath)) {
            $resolvedPath = $configPath;
        } else {
            return new MissingConfig();
        }

        $config = require $resolvedPath;

        if (!($config instanceof ConfigInterface)) {
            throw new InvalidConfigurationException(
                sprintf(
                    'Return value of module %s is expected to be an instance of %s but was not',
                    $resolvedPath,
                    ConfigInterface::class
                )
            );
        }

        return $config;
    }
}
