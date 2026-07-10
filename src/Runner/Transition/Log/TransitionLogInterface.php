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

namespace Tappet\Runner\Transition\Log;

use Tappet\Runner\Exception\UnexpectedTransitionException;
use Tappet\Runner\Transition\TransitionInterface;

/**
 * Interface TransitionLogInterface.
 *
 * Ordered log of whole-page-state changes (navigations, modals, etc.)
 * that occurred during a scenario. The cursor tracks which entry is
 * next to be consumed by an assertion.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface TransitionLogInterface
{
    /**
     * Advances the cursor past the current entry if the given transition matches it,
     * otherwise raises an error describing the unexpected transition found instead.
     *
     * @throws UnexpectedTransitionException When an unexpected transition is found.
     */
    public function consumeTransition(TransitionInterface $transition): void;

    /**
     * Formats the log as a human-readable string for error messages.
     * The cursor position is marked with ">".
     */
    public function format(): string;

    /**
     * Fetches the total number of entries in the log.
     */
    public function getCount(): int;

    /**
     * Fetches the index of the next unconsumed log entry.
     */
    public function getCursor(): int;

    /**
     * Fetches all log entries.
     *
     * @return TransitionInterface[]
     */
    public function getEntries(): array;

    /**
     * Appends a new transition entry to the log.
     */
    public function pushTransition(TransitionInterface $transition): void;

    /**
     * Clears the log and resets the cursor. Called before each test.
     */
    public function reset(): void;
}
