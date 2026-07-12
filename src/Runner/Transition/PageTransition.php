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

use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Page\PageInterface;

/**
 * Class PageTransition.
 *
 * Represents a browser navigation to a page.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class PageTransition implements TransitionInterface
{
    public function __construct(
        private readonly PageInterface $page,
        private readonly EnvironmentInterface $environment
    ) {
    }

    /**
     * @inheritDoc
     */
    public function equals(TransitionInterface $other): bool
    {
        return $other instanceof NavigationTransitionInterface && $other->getUrl() === $this->getUrl();
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return 'navigation to "' . $this->getUrl() . '"';
    }

    /**
     * Fetches the URL this navigation is expected to reach.
     */
    public function getUrl(): string
    {
        return $this->environment->buildPageUrl($this->page);
    }
}
