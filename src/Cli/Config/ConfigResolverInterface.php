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
use Tappet\Core\Exception\MissingConfigurationException;

/**
 * Interface ConfigResolverInterface.
 *
 * Resolves the config from `tappet.config.php`.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ConfigResolverInterface
{
    /**
     * Resolves the Tappet config.
     *
     * @throws MissingConfigurationException When the configuration file `tappet.config.php` is missing.
     * @throws InvalidConfigurationException When the configuration file `tappet.config.php` is invalid.
     */
    public function resolveConfig(): ConfigInterface;
}
