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
 * Interface MatcherRegistryInterface.
 *
 * Maps matcher types to their handlers and dispatches matching accordingly.
 *
 * Unlike e.g. RegionAssertionRegistryInterface, a matcher's type is never sniffed from the DOM -
 * it defaults to "default" and may be overridden per column/region by the consuming app (e.g. via
 * a `data-[...]-match-type` attribute). This allows a given MatcherInterface (e.g. Text)
 * to be handled differently for a particular column/region.
 *
 * @template TAutomation of AutomationInterface
 * @template TContext of ContextInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface MatcherRegistryInterface
{
    /**
     * Dispatches the given matcher to the handler registered for the given matcher type.
     *
     * @param ContextInterface $context The automation-specific context (e.g. DOM element reference)
     *                                  wrapping the target to match against.
     */
    public function handleMatcher(
        string $matcherType,
        MatcherInterface $matcher,
        ContextInterface $context,
        AutomationInterface $automation
    ): void;

    /**
     * Registers a handler for the given matcher type.
     *
     * @param MatchHandlerInterface<TAutomation, MatcherInterface, TContext> $handler
     */
    public function registerMatchHandler(string $matcherType, MatchHandlerInterface $handler): void;
}
