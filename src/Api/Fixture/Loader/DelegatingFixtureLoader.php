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

namespace Tappet\Api\Fixture\Loader;

use InvalidArgumentException;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;

/**
 * Class DelegatingFixtureLoader.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class DelegatingFixtureLoader implements DelegatingFixtureLoaderInterface
{
    /**
     * @var array<class-string<FixtureInterface<ModelInterface>>, LoaderPairInterface<FixtureInterface<ModelInterface>, ModelInterface>>
     */
    private $loaderPairs = [];

    /**
     * @inheritDoc
     *
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function loadFixture(FixtureInterface $fixture): ModelInterface
    {
        $loaderPair = $this->loaderPairs[$fixture::class] ?? null;

        if ($loaderPair === null) {
            throw new InvalidArgumentException(sprintf(
                'No loader pair found for fixture class "%s"',
                $fixture::class
            ));
        }

        return $loaderPair->getLoader()($fixture);
    }

    /**
     * @inheritDoc
     *
     * @template TFixture of FixtureInterface<TModel>
     * @template TModel of ModelInterface
     *
     * @param FixtureLoaderInterface<TFixture, TModel> $loader
     */
    public function registerLoader(FixtureLoaderInterface $loader): void
    {
        foreach ($loader->getLoaderPairs() as $fixtureClass => $loaderPair) {
            if (isset($this->loaderPairs[$fixtureClass])) {
                throw new InvalidArgumentException(sprintf(
                    'Loader for fixture class "%s" already registered',
                    $fixtureClass
                ));
            }

            $this->loaderPairs[$fixtureClass] = $loaderPair;
        }
    }

    /**
     * @inheritDoc
     *
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function unloadFixture(FixtureInterface $fixture, ModelInterface $model): void
    {
        $loaderPair = $this->loaderPairs[$fixture::class] ?? null;

        if ($loaderPair === null) {
            throw new InvalidArgumentException(sprintf(
                'No loader pair found for fixture class "%s"',
                $fixture::class
            ));
        }

        $loaderPair->getUnloader()($fixture, $model);
    }
}
