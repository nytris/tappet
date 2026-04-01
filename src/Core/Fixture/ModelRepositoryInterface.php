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

namespace Tappet\Core\Fixture;

/**
 * Interface ModelRepositoryInterface.
 *
 * Loads fixtures via the Fixture API, storing the corresponding models
 * that represent their loaded state.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ModelRepositoryInterface extends ModelProviderInterface
{
    /**
     * Loads the given fixture into the repository via the fixture API.
     *
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): void;

    /**
     * Loads multiple fixtures into the repository at once via the fixture API.
     *
     * @param array<string, FixtureInterface<ModelInterface>> $fixtures
     */
    public function loadMultipleFixtures(array $fixtures): void;

    /**
     * Purges all fixtures from the repository.
     */
    public function purge(): void;
}
