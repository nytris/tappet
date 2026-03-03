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

namespace Tappet\Tests\Unit\Core\Suite;

use Tappet\Core\Scenario\ScenarioInterface;
use Tappet\Core\Suite\Suite;
use Tappet\Tests\AbstractTestCase;

/**
 * Class SuiteTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class SuiteTest extends AbstractTestCase
{
    public function testGetDescriptionReturnsDescription(): void
    {
        $suite = new Suite('my suite description', []);

        static::assertSame('my suite description', $suite->getDescription());
    }

    public function testGetScenariosReturnsEmptyArrayWhenNoneProvided(): void
    {
        $suite = new Suite('my suite', []);

        static::assertSame([], $suite->getScenarios());
    }

    public function testGetScenariosReturnsProvidedScenarios(): void
    {
        $scenario1 = mock(ScenarioInterface::class);
        $scenario2 = mock(ScenarioInterface::class);

        $suite = new Suite('my suite', [$scenario1, $scenario2]);

        static::assertSame([$scenario1, $scenario2], $suite->getScenarios());
    }
}
