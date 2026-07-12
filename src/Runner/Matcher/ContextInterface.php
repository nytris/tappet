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
 * Interface ContextInterface.
 *
 * Marker interface for an automation-specific context wrapping the target (e.g. a cell/item)
 * that a MatcherInterface is to be matched against. Opaque; each automation adapter (e.g.
 * Tappet\Cypress\Automation\Matcher\ContextInterface for Cypress) extends this with whatever accessor(s)
 * it needs, and only that adapter's own automation class knows how to unwrap one.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ContextInterface
{
}
