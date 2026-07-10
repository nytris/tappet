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

namespace Tappet\Runner\Automation\Matcher;

use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Runner\Matcher\ContextInterface;
use Tappet\Runner\Matcher\MatcherInterface;

/**
 * Interface MatchHandlerInterface.
 *
 * Handles matching for one or more MatcherInterface implementations.
 *
 * @template TAutomation of AutomationInterface
 * @template TMatcher of MatcherInterface
 * @template TContext of ContextInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface MatchHandlerInterface
{
    /**
     * Returns a map of MatcherInterface FQCNs to callable handlers.
     *
     * Each key is the fully qualified class name of a MatcherInterface implementation,
     * and each value is a callable that accepts an instance of that class, the automation-specific
     * context (e.g. wrapping a Cypress chainable) representing the cell/item to match, and the automation,
     * and performs the corresponding match.
     *
     * @return array<class-string<TMatcher>, callable(TMatcher, TContext, TAutomation): void>
     */
    public function getHandlers(): array;
}
