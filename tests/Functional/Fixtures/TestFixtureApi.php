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

use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;

/**
 * Class TestFixtureApi.
 *
 * Stub fixture API for use in functional tests.
 * Pretends to have handled the fixture load, returning serialised TestModel instances.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestFixtureApi
{
    /**
     * @var array<array{fixture: string, model: string}>
     */
    public array $purgedModels = [];

    /**
     * @param class-string<FixtureInterface<ModelInterface>> $fixtureClass
     */
    public function loadFixture(string $fixtureClass, string $serialisedFixture): string
    {
        return serialize(new TestModel($serialisedFixture));
    }

    public function loadMultipleFixtures(string $serialisedFixtures): string
    {
        $fixtures = unserialize($serialisedFixtures);
        $models = [];

        foreach ($fixtures as $handle => $fixture) {
            $models[$handle] = new TestModel(serialize($fixture));
        }

        return serialize($models);
    }

    /**
     * @param array<array{fixture: string, model: string}> $modelsToPurge
     */
    public function purge(array $modelsToPurge): void
    {
        $this->purgedModels = $modelsToPurge;
    }
}
