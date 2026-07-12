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

namespace Tappet\Runner\Transition;

/**
 * Interface TransitionInterface.
 *
 * Represents a whole-page-state change logged by the test runner,
 * such as a navigation to a new URL or a modal appearing or disappearing.
 *
 * The test runner keeps an ordered log of transitions that occurred during
 * a scenario. Assertions check the log to verify that expected transitions
 * happened in the correct order and fail immediately when unexpected transitions
 * are detected.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface TransitionInterface
{
    /**
     * Checks whether this transition is equivalent to the given other transition.
     */
    public function equals(TransitionInterface $other): bool;

    /**
     * Fetches a human-readable description of this transition for error messages.
     */
    public function getDescription(): string;
}