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

use RuntimeException;

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
     * @var object
     */
    private $fixtureApi;
    /**
     * @var array<class-string<FixtureInterface<ModelInterface>>, array<string, array{'fixture': FixtureInterface<ModelInterface>, 'model': ModelInterface}>>
     */
    private $fixtureModels = [];

    public function __construct(object $fixtureApi)
    {
        $this->fixtureApi = $fixtureApi;
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

        return $this->fixtureModels[$modelClass][$handle]['model'];
    }

    /**
     * @inheritDoc
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): void
    {
        $response = $this->fixtureApi->loadFixture($fixture::class, serialize($fixture));

        $model = unserialize($response);

        if (!($model instanceof ($fixture::getModelClass()))) {
            throw new RuntimeException(sprintf(
                'Fixture "%s" model of type "%s" returned from API does not match expected type "%s"',
                $fixture::class,
                $model::class,
                $fixture::getModelClass()
            ));
        }

        $this->fixtureModels[$fixture::getModelClass()][$handle] = [
            'fixture' => $fixture,
            'model' => $model,
        ];
    }

    /**
     * @inheritDoc
     */
    public function loadMultipleFixtures(array $fixtures): void
    {
        $response = $this->fixtureApi->loadMultipleFixtures(serialize($fixtures));
        $models = unserialize($response);

        foreach ($fixtures as $handle => $fixture) {
            $model = $models[$handle];

            if (!($model instanceof ($fixture::getModelClass()))) {
                throw new RuntimeException(sprintf(
                    'Fixture "%s" model of type "%s" returned from API does not match expected type "%s"',
                    $fixture::class,
                    $model::class,
                    $fixture::getModelClass()
                ));
            }

            $this->fixtureModels[$fixture::getModelClass()][$handle] = [
                'fixture' => $fixture,
                'model' => $model,
            ];
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

        $modelsToPurge = [];

        foreach ($this->fixtureModels as $models) {
            foreach ($models as $data) {
                // Models should be purged in reverse order of loading due to likely dependencies between them.
                array_unshift($modelsToPurge, [
                    'fixture' => serialize($data['fixture']),
                    'model' => serialize($data['model'])
                ]);
            }
        }

        $this->fixtureApi->purge($modelsToPurge);

        $this->fixtureModels = [];
    }
}
