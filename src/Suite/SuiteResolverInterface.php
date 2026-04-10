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

namespace Tappet\Suite;

use Tappet\Core\Exception\InvalidConfigurationException;
use Tappet\Core\Exception\MissingConfigurationException;

/**
 * Interface SuiteResolverInterface.
 *
 * Resolves the Tappet suite configuration from `tappet.{suite-name}.suite.php`.
 *
 * @template TSuite of SuiteInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface SuiteResolverInterface
{
    /**
     * Resolves the Tappet suite config.
     *
     * @return TSuite
     * @throws MissingConfigurationException When the configuration file `tappet.{suite-name}.suite.php` is missing.
     * @throws InvalidConfigurationException When the configuration file `tappet.{suite-name}.suite.php` is invalid.
     */
    public function resolveSuite(string $suiteName): SuiteInterface;
}
