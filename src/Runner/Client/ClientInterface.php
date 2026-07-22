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

namespace Tappet\Runner\Client;

use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Fixture\LoadedFixtureInterface;

/**
 * Interface ClientInterface.
 *
 * Handles communication with the Tappet API.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ClientInterface
{
    /**
     * Loads a fixture via the API and returns the loaded fixture model.
     *
     * @template TFixture of FixtureInterface<TModel>
     * @template TModel of ModelInterface
     *
     * @param TFixture $fixture The fixture to load
     * @return LoadedFixtureInterface<TFixture, TModel>
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): LoadedFixtureInterface;

    /**
     * Loads multiple fixtures via the API and returns the loaded fixture models.
     *
     * @template TFixture of FixtureInterface<TModel>
     * @template TModel of ModelInterface
     *
     * @param array<TFixture> $fixtures
     * @return array<class-string<TModel>, array<string, LoadedFixtureInterface<TFixture, TModel>>>
     */
    public function loadMultipleFixtures(array $fixtures): array;

    /**
     * Purges all fixtures from the API.
     *
     * Fixtures implementing DeferredPurgeFixtureInterface are sent to the API separately,
     * as models to defer purging of rather than to purge immediately.
     *
     * @param array<class-string<ModelInterface>, array<string, LoadedFixtureInterface<FixtureInterface<ModelInterface>, ModelInterface>>> $fixtureModels
     */
    public function purge(array $fixtureModels): void;
}
