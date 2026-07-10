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

namespace Tappet\Tests\Unit\Runner\Fixture;

use Mockery\MockInterface;
use RuntimeException;
use Tappet\Common\Exception\FixtureModelMismatchException;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Client\ClientInterface;
use Tappet\Runner\Fixture\LoadedFixture;
use Tappet\Runner\Fixture\ModelRepository;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ModelRepositoryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ModelRepositoryTest extends AbstractTestCase
{
    private ClientInterface&MockInterface $client;
    private ModelRepository $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = mock(ClientInterface::class);

        $this->modelRepository = new ModelRepository($this->client);
    }

    public function testGetFixtureModelThrowsWhenModelNotLoaded(): void
    {
        $this->expectException(RuntimeException::class);

        $this->modelRepository->getFixtureModel(ModelRepositoryTestModel::class, 'myHandle');
    }

    public function testGetFixtureModelReturnsModelAfterLoadFixture(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();
        $loadedFixture = new LoadedFixture($fixture, $model, 'myHandle');
        $this->client->allows('loadFixture')->andReturn($loadedFixture);

        $this->modelRepository->loadFixture('myHandle', $fixture);
        $result = $this->modelRepository->getFixtureModel(ModelRepositoryTestModel::class, 'myHandle');

        static::assertInstanceOf(ModelRepositoryTestModel::class, $result);
    }

    public function testLoadFixtureCallsClientWithHandleAndFixture(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();
        $loadedFixture = new LoadedFixture($fixture, $model, 'myHandle');

        $this->client->expects()
            ->loadFixture('myHandle', $fixture)
            ->once()
            ->andReturn($loadedFixture);

        $this->modelRepository->loadFixture('myHandle', $fixture);
    }

    public function testLoadFixtureThrowsWhenExceptionPropagatesFromClient(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $this->client->allows('loadFixture')->andThrow(
            new FixtureModelMismatchException('type mismatch')
        );

        $this->expectException(FixtureModelMismatchException::class);

        $this->modelRepository->loadFixture('myHandle', $fixture);
    }

    public function testPurgeDoesNothingWhenNoModelsLoaded(): void
    {
        $this->client->expects('purge')->never();

        $this->modelRepository->purge();
    }

    public function testPurgeCallsClientWithLoadedModelsMap(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();
        $loadedFixture = new LoadedFixture($fixture, $model, 'myHandle');
        $this->client->allows('loadFixture')->andReturn($loadedFixture);
        $this->modelRepository->loadFixture('myHandle', $fixture);

        $this->client->expects()
            ->purge([
                ModelRepositoryTestModel::class => ['myHandle' => $loadedFixture],
            ])
            ->once();

        $this->modelRepository->purge();
    }

    public function testLoadMultipleFixturesCallsClientWithFixtures(): void
    {
        $fixture1 = new ModelRepositoryTestFixture();
        $fixture2 = new ModelRepositoryTestFixture2();
        $model1 = new ModelRepositoryTestModel();
        $model2 = new ModelRepositoryTestModel2();
        $loadedFixture1 = new LoadedFixture($fixture1, $model1, 'myHandle1');
        $loadedFixture2 = new LoadedFixture($fixture2, $model2, 'myHandle2');

        $this->client->expects()
            ->loadMultipleFixtures(['myHandle1' => $fixture1, 'myHandle2' => $fixture2])
            ->once()
            ->andReturn([
                ModelRepositoryTestModel::class => ['myHandle1' => $loadedFixture1],
                ModelRepositoryTestModel2::class => ['myHandle2' => $loadedFixture2],
            ]);

        $this->modelRepository->loadMultipleFixtures([
            'myHandle1' => $fixture1,
            'myHandle2' => $fixture2,
        ]);
    }

    public function testLoadMultipleFixturesStoresModelsForRetrieval(): void
    {
        $fixture1 = new ModelRepositoryTestFixture();
        $fixture2 = new ModelRepositoryTestFixture2();
        $model1 = new ModelRepositoryTestModel();
        $model2 = new ModelRepositoryTestModel2();
        $loadedFixture1 = new LoadedFixture($fixture1, $model1, 'myHandle1');
        $loadedFixture2 = new LoadedFixture($fixture2, $model2, 'myHandle2');
        $this->client->allows('loadMultipleFixtures')->andReturn([
            ModelRepositoryTestModel::class => ['myHandle1' => $loadedFixture1],
            ModelRepositoryTestModel2::class => ['myHandle2' => $loadedFixture2],
        ]);

        $this->modelRepository->loadMultipleFixtures([
            'myHandle1' => $fixture1,
            'myHandle2' => $fixture2,
        ]);

        static::assertEquals(
            $model1,
            $this->modelRepository->getFixtureModel(ModelRepositoryTestModel::class, 'myHandle1')
        );
        static::assertEquals(
            $model2,
            $this->modelRepository->getFixtureModel(ModelRepositoryTestModel2::class, 'myHandle2')
        );
    }

    public function testLoadMultipleFixturesThrowsWhenExceptionPropagatesFromClient(): void
    {
        $fixture1 = new ModelRepositoryTestFixture();
        $this->client->allows('loadMultipleFixtures')->andThrow(
            new FixtureModelMismatchException('type mismatch')
        );

        $this->expectException(FixtureModelMismatchException::class);

        $this->modelRepository->loadMultipleFixtures(['myHandle1' => $fixture1]);
    }

    public function testPurgeClearsModelsFromRepository(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();
        $loadedFixture = new LoadedFixture($fixture, $model, 'myHandle');
        $this->client->allows('loadFixture')->andReturn($loadedFixture);
        $this->client->allows('purge');
        $this->modelRepository->loadFixture('myHandle', $fixture);
        $this->modelRepository->purge();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Fixture model not found for "%s" with handle "%s"',
                ModelRepositoryTestModel::class,
                'myHandle'
            )
        );

        $this->modelRepository->getFixtureModel(ModelRepositoryTestModel::class, 'myHandle');
    }
}

/**
 * Concrete fixture class used as a test double.
 *
 * @implements FixtureInterface<ModelRepositoryTestModel>
 */
class ModelRepositoryTestFixture implements FixtureInterface
{
    public static function getModelClass(): string
    {
        return ModelRepositoryTestModel::class;
    }
}

/**
 * @implements FixtureInterface<ModelRepositoryTestModel2>
 */
class ModelRepositoryTestFixture2 implements FixtureInterface
{
    public static function getModelClass(): string
    {
        return ModelRepositoryTestModel2::class;
    }
}

/**
 * Concrete model class used as a test double.
 */
class ModelRepositoryTestModel implements ModelInterface
{
}
class ModelRepositoryTestModel2 implements ModelInterface
{
}
