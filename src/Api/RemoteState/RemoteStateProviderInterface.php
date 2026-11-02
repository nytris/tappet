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
 * Interface RemoteStateProviderInterface.
 *
 * Implemented by a consuming application to expose one or more values via the Tappet API's
 * remote state endpoint, for assertion by a RemoteStateAssertionInterface in a GUI test -
 * typically a value that is deliberately never surfaced anywhere in the UI.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RemoteStateProviderInterface
{
    /**
     * Returns a map of remote state handles to callables that fetch the corresponding value.
     *
     * @return array<string, callable(): mixed>
     */
    public function getProviders(): array;
}
