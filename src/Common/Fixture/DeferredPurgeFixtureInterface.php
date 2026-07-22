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

namespace Tappet\Common\Fixture;

/**
 * Interface DeferredPurgeFixtureInterface.
 *
 * Marker interface for a fixture whose purge must be deferred until the Cypress runner Node.js process itself
 * exits, rather than purged as soon as the scenario or module that loaded it concludes. Useful for
 * fixtures that need to remain in place for as long as a test runner window stays open (e.g. Cypress
 * "open" mode, where the Node.js controller process only exits once the window is closed).
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface DeferredPurgeFixtureInterface
{
}
