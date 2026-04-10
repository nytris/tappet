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

namespace Tappet\Core\Exception;

use Exception;

/**
 * Class MissingConfigurationException.
 *
 * Raised when the configuration file `tappet.{suite-name}.suite.php` is missing.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MissingConfigurationException extends Exception implements ConfigurationExceptionInterface
{
}
