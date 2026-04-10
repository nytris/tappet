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
        private readonly string $configFileName = 'tappet.config.php'
    ) {
    }

    /**
     * @inheritDoc
     */
    public function resolveConfig(): ConfigInterface
    {
        $configPath = $this->projectRoot . DIRECTORY_SEPARATOR . $this->configFileName;

        if (!is_file($configPath)) {
            return new MissingConfig();
        }

        $config = require $configPath;

        if (!($config instanceof ConfigInterface)) {
            throw new InvalidConfigurationException(
                sprintf(
                    'Return value of module %s is expected to be an instance of %s but was not',
                    $configPath,
                    ConfigInterface::class
                )
            );
        }

        return $config;
    }
}
