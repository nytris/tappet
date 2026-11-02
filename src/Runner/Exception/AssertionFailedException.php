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
 * Class AssertionFailedException.
 *
 * Raised when an assertion fails outside of the automation layer's own retry/matching
 * mechanism - for example, when matching a value fetched from a remote state assertion
 * rather than a value present within the DOM.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertionFailedException extends LogicException
{
}
