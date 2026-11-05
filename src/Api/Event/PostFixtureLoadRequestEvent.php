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

namespace Tappet\Api\Event;

use Tappet\Common\Event\EventInterface;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\LoadedFixtureInterface;
use Tappet\Common\Fixture\ModelInterface;

/**
 * Class PostFixtureLoadRequestEvent.
 *
 * Raised during a fixture load API call, after the fixtures have all been loaded.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class PostFixtureLoadRequestEvent implements EventInterface
{
    /**
     * @param array<class-string<ModelInterface>, array<string, LoadedFixtureInterface<FixtureInterface<ModelInterface>, ModelInterface>>> $loadedFixturesByModelClass
     */
    public function __construct(
        private readonly array $loadedFixturesByModelClass
    ) {
    }

    /**
     * Fetches the loaded fixture models for the given model class, keyed by handle.
     *
     * @template TModel of ModelInterface
     *
     * @param class-string<TModel> $modelClass
     * @return array<string, TModel>
     */
    public function getFixtureModels(string $modelClass): array
    {
        return array_map(
            static function (LoadedFixtureInterface $fixture) {
                return $fixture->getModel();
            },
            $this->loadedFixturesByModelClass[$modelClass] ?? []
        );
    }

    /**
     * Fetches all loaded fixtures, keyed by model class and handle.
     *
     * @return array<class-string<ModelInterface>, array<string, LoadedFixtureInterface<FixtureInterface<ModelInterface>, ModelInterface>>>
     */
    public function getLoadedFixtures(): array
    {
        return $this->loadedFixturesByModelClass;
    }
}
