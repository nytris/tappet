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

use InvalidArgumentException;
use Mockery\MockInterface;
use Tappet\Api\Fixture\Loader\DelegatingFixtureLoader;
use Tappet\Api\Fixture\Loader\FixtureLoaderInterface;
use Tappet\Api\Fixture\Loader\LoaderPairInterface;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class DelegatingFixtureLoaderTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class DelegatingFixtureLoaderTest extends AbstractTestCase
{
    private DelegatingFixtureLoader $delegatingFixtureLoader;

    public function setUp(): void
    {
        parent::setUp();

        $this->delegatingFixtureLoader = new DelegatingFixtureLoader();
    }

    public function testLoadFixtureCallsLoaderWithFixtureAndReturnsModel(): void
    {
        $fixture = new DelegatingFixtureLoaderTestFixture();
        $model = new DelegatingFixtureLoaderTestModel();
        $capturedFixture = null;
        /** @var LoaderPairInterface<DelegatingFixtureLoaderTestFixture, DelegatingFixtureLoaderTestModel>&MockInterface $loaderPair */
        $loaderPair = mock(LoaderPairInterface::class, [
            'getLoader' => function ($fixture) use ($model, &$capturedFixture) {
                $capturedFixture = $fixture;
                return $model;
            },
        ]);
        /** @var FixtureLoaderInterface<DelegatingFixtureLoaderTestFixture, DelegatingFixtureLoaderTestModel>&MockInterface $fixtureLoader */
        $fixtureLoader = mock(FixtureLoaderInterface::class, [
            'getLoaderPairs' => [
                DelegatingFixtureLoaderTestFixture::class => $loaderPair,
            ],
        ]);
        $this->delegatingFixtureLoader->registerLoader($fixtureLoader);

        $result = $this->delegatingFixtureLoader->loadFixture($fixture);

        static::assertSame($fixture, $capturedFixture);
        static::assertSame($model, $result);
    }

    public function testLoadFixtureThrowsWhenNoLoaderPairFound(): void
    {
        $fixture = new DelegatingFixtureLoaderTestFixture();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('No loader pair found for fixture class "%s"', DelegatingFixtureLoaderTestFixture::class)
        );

        $this->delegatingFixtureLoader->loadFixture($fixture);
    }

    public function testRegisterLoaderThrowsWhenFixtureClassAlreadyRegistered(): void
    {
        /** @var LoaderPairInterface<DelegatingFixtureLoaderTestFixture, DelegatingFixtureLoaderTestModel>&MockInterface $loaderPair */
        $loaderPair = mock(LoaderPairInterface::class);
        /** @var FixtureLoaderInterface<DelegatingFixtureLoaderTestFixture, DelegatingFixtureLoaderTestModel>&MockInterface $fixtureLoader */
        $fixtureLoader = mock(FixtureLoaderInterface::class);
        $fixtureLoader->allows('getLoaderPairs')->andReturn([
            DelegatingFixtureLoaderTestFixture::class => $loaderPair,
        ]);
        $this->delegatingFixtureLoader->registerLoader($fixtureLoader);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Loader for fixture class "%s" already registered', DelegatingFixtureLoaderTestFixture::class)
        );

        $this->delegatingFixtureLoader->registerLoader($fixtureLoader);
    }

    public function testUnloadFixtureCallsUnloaderWithFixtureAndModel(): void
    {
        $fixture = new DelegatingFixtureLoaderTestFixture();
        $model = new DelegatingFixtureLoaderTestModel();
        /** @var LoaderPairInterface<DelegatingFixtureLoaderTestFixture, DelegatingFixtureLoaderTestModel>&MockInterface $loaderPair */
        $loaderPair = mock(LoaderPairInterface::class);
        /** @var FixtureLoaderInterface<DelegatingFixtureLoaderTestFixture, DelegatingFixtureLoaderTestModel>&MockInterface $fixtureLoader */
        $fixtureLoader = mock(FixtureLoaderInterface::class);
        $capturedArgs = [];
        $loaderPair->allows('getUnloader')->andReturn(function ($fixture, $model) use (&$capturedArgs) {
            $capturedArgs = [$fixture, $model];
        });
        $fixtureLoader->allows('getLoaderPairs')->andReturn([
            DelegatingFixtureLoaderTestFixture::class => $loaderPair,
        ]);
        $this->delegatingFixtureLoader->registerLoader($fixtureLoader);

        $this->delegatingFixtureLoader->unloadFixture($fixture, $model);

        static::assertSame([$fixture, $model], $capturedArgs);
    }

    public function testUnloadFixtureThrowsWhenNoLoaderPairFound(): void
    {
        $fixture = new DelegatingFixtureLoaderTestFixture();
        $model = new DelegatingFixtureLoaderTestModel();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('No loader pair found for fixture class "%s"', DelegatingFixtureLoaderTestFixture::class)
        );

        $this->delegatingFixtureLoader->unloadFixture($fixture, $model);
    }
}

/**
 * Concrete fixture class used as a test double.
 *
 * @implements FixtureInterface<DelegatingFixtureLoaderTestModel>
 */
class DelegatingFixtureLoaderTestFixture implements FixtureInterface
{
    public static function getModelClass(): string
    {
        return DelegatingFixtureLoaderTestModel::class;
    }
}

/**
 * Concrete model class used as a test double.
 */
class DelegatingFixtureLoaderTestModel implements ModelInterface
{
}