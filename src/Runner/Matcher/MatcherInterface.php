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

namespace Tappet\Runner\Matcher;

/**
 * Interface MatcherInterface.
 *
 * Represents a value used within region assertions such as ExpectTable and ExpectList
 * (e.g. a table cell or list item), describing how that cell/item should be matched.
 * Unlike an AssertionInterface, a matcher does not perform its own matching logic -
 * this is instead abstracted out to a MatchHandlerInterface implementation
 * registered against a MatcherRegistryInterface, so that the automation-specific
 * (e.g. Cypress) API calls involved live outside this core library.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface MatcherInterface
{
}
