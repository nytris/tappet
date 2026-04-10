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

namespace Tappet\Tests\Unit\Api\Fixture\Loader;

use Tappet\Api\Fixture\Loader\LoaderPair;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class LoaderPairTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class LoaderPairTest extends AbstractTestCase
{
    /**
     * @var LoaderPair<FixtureInterface<ModelInterface>, ModelInterface>
     */
    private LoaderPair $loaderPair;
    /**
     * @var callable(FixtureInterface<ModelInterface>): ModelInterface
     */
    private $loader;
    /**
     * @var callable(FixtureInterface<ModelInterface>, ModelInterface): void
     */
    private $unloader;

    public function setUp(): void
    {
        parent::setUp();

        $model = mock(ModelInterface::class);
        $this->loader = fn (FixtureInterface $fixture): ModelInterface => $model;
        $this->unloader = function (FixtureInterface $fixture, ModelInterface $model): void {};

        $this->loaderPair = new LoaderPair($this->loader, $this->unloader);
    }

    public function testGetLoaderReturnsLoader(): void
    {
        static::assertSame($this->loader, $this->loaderPair->getLoader());
    }

    public function testGetUnloaderReturnsUnloader(): void
    {
        static::assertSame($this->unloader, $this->loaderPair->getUnloader());
    }
}
