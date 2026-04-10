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

use Throwable;

/**
 * Interface ExceptionInterface.
 *
 * Implemented by all exceptions raised by Tappet.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ExceptionInterface extends Throwable
{
}
