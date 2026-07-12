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
 * Class UnexpectedTransitionException.
 *
 * Raised when a transition (e.g. page navigation or modal appearance/disappearance)
 * consumed from a TransitionLog does not match the logged entry at the current cursor.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class UnexpectedTransitionException extends LogicException
{
}
