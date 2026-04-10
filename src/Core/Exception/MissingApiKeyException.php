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
 * Class MissingApiKeyException.
 *
 * Raised when no API key is provided via --api-key or the TAPPET_API_KEY environment variable.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MissingApiKeyException extends Exception implements ConfigurationExceptionInterface
{
}
