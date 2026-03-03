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

    public function testLoadFixtureCallsFixtureApiWithHandle(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();

        $this->fixtureApi->expects()
            ->loadFixture('myHandle', serialize($fixture))
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

    public function testPurgeCallsFixtureApiWithLoadedHandles(): void
    {
        $fixture = new ModelRepositoryTestFixture();
        $model = new ModelRepositoryTestModel();
        $this->fixtureApi->allows('loadFixture')->andReturn(serialize($model));
        $this->modelRepository->loadFixture('myHandle', $fixture);

        $this->fixtureApi->expects()
            ->purge([ModelRepositoryTestModel::class => ['myHandle']])
            ->once();

        $this->modelRepository->purge();
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
 */
class ModelRepositoryTestFixture implements FixtureInterface
{
    public function getModelFqcn(): string
    {
        return ModelRepositoryTestModel::class;
    }
}

/**
 * Concrete model class used as a test double.
 */
class ModelRepositoryTestModel implements ModelInterface
{
}

/**
 * Concrete model class used to test type mismatch.
 */
class ModelRepositoryTestWrongModel implements ModelInterface
{
}
