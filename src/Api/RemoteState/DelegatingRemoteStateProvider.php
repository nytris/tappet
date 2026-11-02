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

use Tappet\Common\Exception\InvalidArgumentException;

/**
 * Class DelegatingRemoteStateProvider.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class DelegatingRemoteStateProvider implements DelegatingRemoteStateProviderInterface
{
    /**
     * @var array<string, callable(): mixed>
     */
    private array $providers = [];

    /**
     * @inheritDoc
     */
    public function fetchRemoteState(string $handle): mixed
    {
        if (!array_key_exists($handle, $this->providers)) {
            throw new InvalidArgumentException(sprintf(
                'No remote state provider registered for handle "%s"',
                $handle
            ));
        }

        return ($this->providers[$handle])();
    }

    /**
     * @inheritDoc
     */
    public function registerProvider(RemoteStateProviderInterface $provider): void
    {
        foreach ($provider->getProviders() as $handle => $callback) {
            if (isset($this->providers[$handle])) {
                throw new InvalidArgumentException(sprintf(
                    'Provider for remote state handle "%s" already registered',
                    $handle
                ));
            }

            $this->providers[$handle] = $callback;
        }
    }
}
