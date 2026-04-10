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

namespace Tappet\Tests\Functional\Fixtures;

use Tappet\Cli\Environment\EnvironmentInterface;

/**
 * Class TestEnvironment.
 *
 * Stub implementation of EnvironmentInterface for use in functional tests.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestEnvironment implements EnvironmentInterface
{
    /**
     * @param array<string, string> $variables
     */
    public function __construct(
        private array $variables = []
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getEnvironmentVariable(string $name): ?string
    {
        return $this->variables[$name] ?? null;
    }

    /**
     * Sets an environment variable.
     */
    public function setVariable(string $name, string $value): void
    {
        $this->variables[$name] = $value;
    }

    /**
     * Unsets an environment variable.
     */
    public function unsetVariable(string $name): void
    {
        unset($this->variables[$name]);
    }
}
