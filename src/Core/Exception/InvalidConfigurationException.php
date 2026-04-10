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
 * Class InvalidConfigurationException.
 *
 * Raised when the configuration file `tappet.config.php` is invalid.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class InvalidConfigurationException extends Exception implements ConfigurationExceptionInterface
{
}
