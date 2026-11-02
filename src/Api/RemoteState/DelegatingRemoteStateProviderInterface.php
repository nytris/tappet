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

namespace Tappet\Api\RemoteState;

/**
 * Interface DelegatingRemoteStateProviderInterface.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface DelegatingRemoteStateProviderInterface
{
    /**
     * Fetches the remote state value for the given handle using a registered provider.
     */
    public function fetchRemoteState(string $handle): mixed;

    /**
     * Registers a new remote state provider, which can provide one or more remote state values.
     */
    public function registerProvider(RemoteStateProviderInterface $provider): void;
}
