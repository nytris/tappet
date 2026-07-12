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

namespace Tappet\Runner\Exception;

use Tappet\Common\Exception\LogicException;

/**
 * Class TransitionLogNotEmptyException.
 *
 * Raised when the transition log (e.g. page navigation or modal appearance/disappearances)
 * is not empty when it is expected to be.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TransitionLogNotEmptyException extends LogicException
{
}
