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

namespace Tappet\Cli\Environment;

/**
 * Interface EnvironmentInterface.
 *
 * Abstracts access to the process environment.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface EnvironmentInterface
{
    /**
     * Fetches the value of an environment variable, or null if it is not set.
     */
    public function getEnvironmentVariable(string $name): ?string;
}
