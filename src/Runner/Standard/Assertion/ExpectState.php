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

use Tappet\Runner\Assertion\StateAssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class ExpectState.
 *
 * Asserts that the given state is present on the page.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectState implements StateAssertionInterface
{
    public function __construct(
        private readonly string $stateHandle
    ) {
    }

    /**
     * Fetches the handle of the state to be asserted.
     */
    public function getStateHandle(): string
    {
        return $this->stateHandle;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performStateAssertion($this);
    }
}
