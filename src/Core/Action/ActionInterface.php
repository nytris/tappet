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

namespace Tappet\Core\Action;

use Tappet\Core\Environment\EnvironmentInterface;

interface ActionInterface
{
    // TODO: Error/fail validation if no OpenPage arrangement (or action, or assertion?) has been provided
    //       by the point the first action is performed.

    public function perform(EnvironmentInterface $environment): void;
}
