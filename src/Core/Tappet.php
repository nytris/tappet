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

namespace Tappet\Core;

use RuntimeException;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Scenario\Scenario;
use Tappet\Core\Scenario\ScenarioInterface;
use Tappet\Core\Suite\Suite;

class Tappet
{
    /**
     * @var callable|null
     */
    private static $describeSuite;
    /**
     * @var EnvironmentInterface|null
     */
    private static $environment;

    /**
     * @param ScenarioInterface[] $scenarios
     */
    public static function describe(string $name, array $scenarios): void
    {
        if (!self::$describeSuite) {
            throw new RuntimeException('Nytris Tappet ::describe() :: No describer set');
        }

        (self::$describeSuite)(new Suite($name, $scenarios));
    }

    public static function it(string $name): ScenarioInterface
    {
        return new Scenario(self::$environment, $name);
    }

    public static function initialise(
        callable $describeSuite,
        EnvironmentInterface $environment
    ): void {
        self::$describeSuite = $describeSuite;
        self::$environment = $environment;
    }

    public static function uninitialise(): void
    {
        self::$describeSuite = null;
        self::$environment = null;
    }
}
