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
 * Class NavigationTransition.
 *
 * Represents a browser navigation to a specific URL.
 * Automatically logged when the AUT's window fires a load event.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class NavigationTransition implements NavigationTransitionInterface
{
    public function __construct(
        private readonly string $url
    ) {
    }

    /**
     * @inheritDoc
     */
    public function equals(TransitionInterface $other): bool
    {
        return $other instanceof NavigationTransitionInterface && $other->getUrl() === $this->url;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'navigation to "' . $this->url . '"';
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
