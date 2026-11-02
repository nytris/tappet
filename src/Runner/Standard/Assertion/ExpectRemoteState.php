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

namespace Tappet\Runner\Standard\Assertion;

use Tappet\Runner\Assertion\RemoteStateAssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Matcher\MatcherInterface;
use Tappet\Runner\Standard\Matcher\ExactText;

/**
 * Class ExpectRemoteState.
 *
 * Asserts that the remote state identified by the given handle, fetched via the Tappet API,
 * matches the given matcher. A plain string may be given instead of a matcher,
 * in which case an {@see ExactText} matcher is created to match it.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectRemoteState implements RemoteStateAssertionInterface
{
    private readonly MatcherInterface $matcher;

    public function __construct(
        private readonly string $remoteStateHandle,
        MatcherInterface|string $matcher
    ) {
        $this->matcher = is_string($matcher) ? new ExactText($matcher) : $matcher;
    }

    /**
     * @inheritDoc
     */
    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    /**
     * @inheritDoc
     */
    public function getRemoteStateHandle(): string
    {
        return $this->remoteStateHandle;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performRemoteStateAssertion($this);
    }
}
