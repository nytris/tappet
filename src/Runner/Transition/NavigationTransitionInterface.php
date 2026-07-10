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
 * Interface NavigationTransitionInterface.
 *
 * Represents a browser navigation to a specific URL.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface NavigationTransitionInterface extends TransitionInterface
{
    /**
     * Fetches the URL this navigation is expected to reach.
     */
    public function getUrl(): string;
}
