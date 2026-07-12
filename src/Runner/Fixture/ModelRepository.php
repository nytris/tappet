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

namespace Tappet\Runner\Fixture;

use RuntimeException;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Client\ClientInterface;

/**
 * Class ModelRepository.
 *
 * Loads fixtures via the Fixture API, storing the corresponding models
 * that represent their loaded state.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ModelRepository implements ModelRepositoryInterface
{
    /**
     * @var array<class-string<ModelInterface>, array<string, LoadedFixtureInterface<FixtureInterface<ModelInterface>, ModelInterface>>>
     */
    private array $fixtureModels = [];

    public function __construct(
        private readonly ClientInterface $client
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getFixtureModel(string $modelClass, string $handle): ModelInterface
    {
        if (!isset($this->fixtureModels[$modelClass][$handle])) {
            throw new RuntimeException(sprintf(
                'Fixture model not found for "%s" with handle "%s"',
                $modelClass,
                $handle
            ));
        }

        return $this->fixtureModels[$modelClass][$handle]->getModel();
    }

    /**
     * @inheritDoc
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): void
    {
        $loadedFixture = $this->client->loadFixture($handle, $fixture);

        $this->fixtureModels[$loadedFixture->getModelClass()][$handle] = $loadedFixture;
    }

    /**
     * @inheritDoc
     */
    public function loadMultipleFixtures(array $fixtures): void
    {
        $loadedFixtures = $this->client->loadMultipleFixtures($fixtures);

        foreach ($loadedFixtures as $modelClass => $loadedFixturesForModelClass) {
            foreach ($loadedFixturesForModelClass as $handle => $loadedFixture) {
                $this->fixtureModels[$modelClass][$handle] = $loadedFixture;
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function purge(): void
    {
        if (empty($this->fixtureModels)) {
            return;
        }

        $this->client->purge($this->fixtureModels);

        $this->fixtureModels = [];
    }
}
