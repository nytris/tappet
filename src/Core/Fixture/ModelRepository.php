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
 */
class ModelRepository implements ModelRepositoryInterface
{
    /**
     * @var object
     */
    private $fixtureApi;
    /**
     * @var array<string, array<string, ModelInterface>>
     */
    private $fixtureModels = [];

    public function __construct(object $fixtureApi)
    {
        $this->fixtureApi = $fixtureApi;
    }

    public function getFixtureModel(string $modelFqcn, string $handle): ModelInterface
    {
        if (!isset($this->fixtureModels[$modelFqcn][$handle])) {
            throw new RuntimeException(sprintf(
                'Fixture model not found for "%s" with handle "%s"',
                $modelFqcn,
                $handle
            ));
        }

        return $this->fixtureModels[$modelFqcn][$handle];
    }

    public function loadFixture(string $handle, FixtureInterface $fixture): void
    {
        $response = $this->fixtureApi->loadFixture($handle, serialize($fixture));

        $model = unserialize($response);

        if (!($model instanceof ($fixture->getModelFqcn()))) {
            throw new RuntimeException(sprintf(
                'Fixture "%s" model of type "%s" returned from API does not match expected type "%s"',
                $fixture::class,
                $model::class,
                $fixture->getModelFqcn()
            ));
        }

        $this->fixtureModels[$fixture->getModelFqcn()][$handle] = $model;
    }

    public function purge(): void
    {
        if (empty($this->fixtureModels)) {
            return;
        }

        $handlesToPurge = [];

        foreach ($this->fixtureModels as $modelFqcn => $models) {
            foreach (array_keys($models) as $handle) {
                $handlesToPurge[$modelFqcn][] = $handle;
            }
        }

        $this->fixtureApi->purge($handlesToPurge);

        $this->fixtureModels = [];
    }
}
