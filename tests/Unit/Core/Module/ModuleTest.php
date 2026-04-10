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

namespace Tappet\Tests\Unit\Core\Module;

use Tappet\Core\Module\Module;
use Tappet\Core\Scenario\ScenarioInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ModuleTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ModuleTest extends AbstractTestCase
{
    public function testGetDescriptionReturnsDescription(): void
    {
        $module = new Module('my module description', []);

        static::assertSame('my module description', $module->getDescription());
    }

    public function testGetScenariosReturnsEmptyArrayWhenNoneProvided(): void
    {
        $module = new Module('my module', []);

        static::assertSame([], $module->getScenarios());
    }

    public function testGetScenariosReturnsProvidedScenarios(): void
    {
        $scenario1 = mock(ScenarioInterface::class);
        $scenario2 = mock(ScenarioInterface::class);

        $module = new Module('my module', [$scenario1, $scenario2]);

        static::assertSame([$scenario1, $scenario2], $module->getScenarios());
    }
}
