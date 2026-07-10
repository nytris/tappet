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

use Tappet\Common\Event\EventDispatcherInterface;
use Tappet\Common\Exception\FixtureModelMismatchException;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Configuration\ConfigurationInterface;
use Tappet\Runner\Event\FixtureLoadEvent;
use Tappet\Runner\Fixture\LoadedFixture;
use Tappet\Runner\Fixture\LoadedFixtureInterface;

/**
 * Class Client.
 *
 * Handles communication with the Tappet API.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Client implements ClientInterface
{
    /**
     * @param object $fixtureApi
     */
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly ConfigurationInterface $configuration,
        private readonly object $fixtureApi
    ) {
    }

    /**
     * @inheritDoc
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): LoadedFixtureInterface
    {
        $response = $this->fixtureApi->loadFixture($fixture::class, serialize($fixture));

        $model = unserialize($response);

        if (!($model instanceof ($fixture::getModelClass()))) {
            throw new FixtureModelMismatchException(sprintf(
                'Fixture "%s" model of type "%s" returned from API does not match expected type "%s"',
                $fixture::class,
                $model::class,
                $fixture::getModelClass()
            ));
        }

        $event = new FixtureLoadEvent(
            [
                $fixture::getModelClass() => [
                    $handle => new LoadedFixture($fixture, $model, $handle)
                ]
            ],
            $this->configuration
        );
        $this->eventDispatcher->dispatch($event);

        return new LoadedFixture($fixture, $model, $handle);
    }

    /**
     * @inheritDoc
     */
    public function loadMultipleFixtures(array $fixtures): array
    {
        $response = $this->fixtureApi->loadMultipleFixtures(serialize($fixtures));
        $models = unserialize($response);

        /** @var array<class-string<ModelInterface>, array<string, LoadedFixtureInterface<FixtureInterface<ModelInterface>, ModelInterface>>> $loadedFixturesByModelClass */
        $loadedFixturesByModelClass = [];

        foreach ($fixtures as $handle => $fixture) {
            $model = $models[$handle];
            $modelClass = $fixture::getModelClass();

            if (!($model instanceof $modelClass)) {
                throw new FixtureModelMismatchException(sprintf(
                    'Fixture "%s" model of type "%s" with handle "%s" returned from API does not match expected type "%s"',
                    $fixture::class,
                    $model::class,
                    $handle,
                    $modelClass
                ));
            }

            $loadedFixturesByModelClass[$modelClass][$handle] = new LoadedFixture($fixture, $model, $handle);
        }

        $event = new FixtureLoadEvent(
            $loadedFixturesByModelClass,
            $this->configuration
        );
        $this->eventDispatcher->dispatch($event);

        return $loadedFixturesByModelClass;
    }

    /**
     * @inheritDoc
     */
    public function purge(array $fixtureModels): void
    {
        /** @var array{fixture: string, model: string}[] $modelsToPurge */
        $modelsToPurge = [];

        foreach ($fixtureModels as $loadedFixtures) {
            foreach ($loadedFixtures as $loadedFixture) {
                // Models should be purged in reverse order of loading due to likely dependencies between them.
                array_unshift($modelsToPurge, [
                    'fixture' => serialize($loadedFixture->getFixture()),
                    'model' => serialize($loadedFixture->getModel())
                ]);
            }
        }

        $this->fixtureApi->purge($modelsToPurge);
    }
}
