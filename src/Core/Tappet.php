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

use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Exception\LogicException;
use Tappet\Core\Module\Module;
use Tappet\Core\Scenario\Scenario;
use Tappet\Core\Scenario\ScenarioInterface;

class Tappet
{
    /**
     * @var callable|null
     */
    private static $describeModule;
    /**
     * @var EnvironmentInterface|null
     */
    private static $environment;

    /**
     * @param ScenarioInterface[] $scenarios
     */
    public static function describe(string $name, array $scenarios): void
    {
        if (!self::$describeModule) {
            throw new LogicException('Nytris Tappet ::describe() :: No describer set');
        }

        (self::$describeModule)(new Module($name, $scenarios));
    }

    public static function it(string $name): ScenarioInterface
    {
        return new Scenario(self::$environment, $name);
    }

    public static function initialise(
        callable $describeModule,
        EnvironmentInterface $environment
    ): void {
        self::$describeModule = $describeModule;
        self::$environment = $environment;
    }

    public static function uninitialise(): void
    {
        self::$describeModule = null;
        self::$environment = null;
    }
}
