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
 * Class MissingApiBaseUrlException.
 *
 * Raised when no API base URL is provided via --api-base-url or the TAPPET_API_BASE_URL environment variable.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MissingApiBaseUrlException extends Exception implements ConfigurationExceptionInterface
{
}
