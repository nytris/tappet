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

namespace Tappet\Tests\Unit\Core\Fixture;

use RuntimeException;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Fixture\ModelRepository;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ModelRepositoryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ModelRepositoryTest extends AbstractTestCase
{
    private object $fixtureApi;
    private ModelRepository $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->fixtureApi = mock();

        $this->modelRepository = new ModelRepository($this->fixtureApi);
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
        $this->fixtureApi->allows('loadFixture')->andReturn(serialize($model));

        $this->modelRepository->loadFixture('myHandle', $fixture);
        $result = $this->modelRepository->getFixtureModel(ModelRepositoryTestModel::class, 'myHandle');

        static::assertInstanceOf(ModelRepositoryTestModel::class, $result);
    }

    public function testLoadFixtureCallsFixtureApiWithFixtureClass(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();

        $this->fixtureApi->expects()
            ->loadFixture($fixture::class, serialize($fixture))
            ->once()
            ->andReturn(serialize($model));

        $this->modelRepository->loadFixture('myHandle', $fixture);
    }

    public function testLoadFixtureThrowsWhenModelTypeDoesNotMatchFixture(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $wrongModel = new ModelRepositoryTestWrongModel();
        $this->fixtureApi->allows('loadFixture')->andReturn(serialize($wrongModel));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Fixture "%s" model of type "%s" returned from API does not match expected type "%s"',
                ModelRepositoryTestFixture::class,
                ModelRepositoryTestWrongModel::class,
                ModelRepositoryTestModel::class
            )
        );

        $this->modelRepository->loadFixture('myHandle', $fixture);
    }

    public function testPurgeDoesNothingWhenNoModelsLoaded(): void
    {
        $this->fixtureApi->expects('purge')->never();

        $this->modelRepository->purge();
    }

    public function testPurgeCallsFixtureApiWithLoadedModels(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();
        $this->fixtureApi->allows('loadFixture')->andReturn(serialize($model));
        $this->modelRepository->loadFixture('myHandle', $fixture);

        $this->fixtureApi->expects()
            ->purge([['fixture' => serialize($fixture), 'model' => serialize($model)]])
            ->once();

        $this->modelRepository->purge();
    }

    public function testPurgeCallsFixtureApiWithLoadedModelsInReverseOrder(): void
    {
        $fixture1 = new ModelRepositoryTestFixture();
        $model1 = new ModelRepositoryTestModel();
        $fixture2 = new ModelRepositoryTestFixture2();
        $model2 = new ModelRepositoryTestModel2();
        $this->fixtureApi->allows('loadFixture')->andReturn(serialize($model1), serialize($model2));
        $this->modelRepository->loadFixture('myHandle1', $fixture1);
        $this->modelRepository->loadFixture('myHandle2', $fixture2);

        $this->fixtureApi->expects()
            ->purge([
                ['fixture' => serialize($fixture2), 'model' => serialize($model2)],
                ['fixture' => serialize($fixture1), 'model' => serialize($model1)],
            ])
            ->once();

        $this->modelRepository->purge();
    }

    public function testLoadMultipleFixturesCallsFixtureApiWithFixtureData(): void
    {
        $fixture1 = new ModelRepositoryTestFixture();
        $fixture2 = new ModelRepositoryTestFixture2();
        $model1 = new ModelRepositoryTestModel();
        $model2 = new ModelRepositoryTestModel2();

        $this->fixtureApi->expects()
            ->loadMultipleFixtures(serialize([
                'myHandle1' => $fixture1,
                'myHandle2' => $fixture2,
            ]))
            ->once()
            ->andReturn(serialize(['myHandle1' => $model1, 'myHandle2' => $model2]));

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
        $this->fixtureApi->allows('loadMultipleFixtures')->andReturn(
            serialize(['myHandle1' => $model1, 'myHandle2' => $model2])
        );

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

    public function testLoadMultipleFixturesThrowsWhenModelTypeDoesNotMatchFixture(): void
    {
        $fixture1 = new ModelRepositoryTestFixture();
        $fixture2 = new ModelRepositoryTestFixture2();
        $model1 = new ModelRepositoryTestModel();
        $wrongModel = new ModelRepositoryTestWrongModel();
        $this->fixtureApi->allows('loadMultipleFixtures')->andReturn(
            serialize(['myHandle1' => $model1, 'myHandle2' => $wrongModel])
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Fixture "%s" model of type "%s" returned from API does not match expected type "%s"',
                ModelRepositoryTestFixture2::class,
                ModelRepositoryTestWrongModel::class,
                ModelRepositoryTestModel2::class
            )
        );

        $this->modelRepository->loadMultipleFixtures([
            'myHandle1' => $fixture1,
            'myHandle2' => $fixture2,
        ]);
    }

    public function testPurgeClearsModelsFromRepository(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();
        $this->fixtureApi->allows('loadFixture')->andReturn(serialize($model));
        $this->fixtureApi->allows('purge');
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

/**
 * Concrete model class used to test type mismatch.
 */
class ModelRepositoryTestWrongModel implements ModelInterface
{
}
