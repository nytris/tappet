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

use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;

/**
 * Interface DelegatingFixtureLoaderInterface.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface DelegatingFixtureLoaderInterface
{
    /**
     * Loads the specified fixture using a registered loader.
     *
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function loadFixture(FixtureInterface $fixture): ModelInterface;

    /**
     * Registers a new fixture loader, which can load one or more types of fixture.
     *
     * @template TSpecificFixture of FixtureInterface<TSpecificModel>
     * @template TSpecificModel of ModelInterface
     *
     * @param FixtureLoaderInterface<TSpecificFixture, TSpecificModel> $loader
     */
    public function registerLoader(FixtureLoaderInterface $loader): void;

    /**
     * Unloads the specified fixture using a registered unloader.
     *
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function unloadFixture(FixtureInterface $fixture, ModelInterface $model): void;
}
