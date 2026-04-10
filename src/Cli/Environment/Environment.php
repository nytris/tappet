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
 * Class Environment.
 *
 * Abstracts access to the process environment.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Environment implements EnvironmentInterface
{
    /**
     * @inheritDoc
     */
    public function getEnvironmentVariable(string $name): ?string
    {
        $value = getenv($name);

        return $value !== false ? $value : null;
    }
}
