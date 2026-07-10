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

namespace Tappet\Common\Exception;

use RuntimeException;

/**
 * Class FixtureModelMismatchException.
 *
 * Raised when a fixture model returned from the API does not match the expected type.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FixtureModelMismatchException extends RuntimeException implements ExceptionInterface
{
}
