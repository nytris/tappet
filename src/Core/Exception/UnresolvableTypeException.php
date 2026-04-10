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
 * Class UnresolvableTypeException.
 *
 * Raised when a type cannot be resolved.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class UnresolvableTypeException extends Exception implements ExceptionInterface
{
}
