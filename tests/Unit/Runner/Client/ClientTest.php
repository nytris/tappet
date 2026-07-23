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

namespace Tappet\Tests\Unit\Runner\Client;

use Mockery;
use Mockery\MockInterface;
use Tappet\Common\Event\EventDispatcherInterface;
use Tappet\Common\Exception\FixtureModelMismatchException;
use Tappet\Common\Fixture\DeferredPurgeFixtureInterface;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Client\Client;
use Tappet\Runner\Configuration\ConfigurationInterface;
use Tappet\Runner\Event\FixtureLoadEvent;
use Tappet\Runner\Fixture\LoadedFixture;
use Tappet\Runner\Fixture\LoadedFixtureInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ClientTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ClientTest extends AbstractTestCase
{
    private Client $client;
    private ConfigurationInterface&MockInterface $configuration;
    /**
     * @var EventDispatcherInterface&MockInterface
     */
    private EventDispatcherInterface&MockInterface $eventDispatcher;
    private object $fixtureApi;

    public function setUp(): void
    {
        parent::setUp();

        $this->configuration = mock(ConfigurationInterface::class);
        $this->eventDispatcher = mock(EventDispatcherInterface::class, [
            'dispatch' => null,
        ]);
        $this->fixtureApi = mock();

        $this->client = new Client(
            $this->eventDispatcher,
            $this->configuration,
            $this->fixtureApi
        );
    }

    public function testLoadFixtureCallsFixtureApiWithFixtureClassAndSerialisedFixture(): void
    {
        $fixture = new ClientTestFixture();
        $model = new ClientTestModel();

        $this->fixtureApi->expects()
            ->loadFixture($fixture::class, serialize($fixture))
            ->once()
            ->andReturn(serialize($model));

        $this->client->loadFixture('myHandle', $fixture);
    }

    public function testLoadFixtureReturnsLoadedFixtureWithCorrectHandle(): void
    {
        $fixture = new ClientTestFixture();
        $model = new ClientTestModel();
        $this->fixtureApi->allows('loadFixture')
            ->andReturn(serialize($model));

        $result = $this->client->loadFixture('myHandle', $fixture);

        static::assertSame('myHandle', $result->getHandle());
    }

    public function testLoadFixtureReturnsLoadedFixtureWithCorrectModel(): void
    {
        $fixture = new ClientTestFixture();
        $model = new ClientTestModel();
        $this->fixtureApi->allows('loadFixture')
            ->andReturn(serialize($model));

        $result = $this->client->loadFixture('myHandle', $fixture);

        static::assertInstanceOf(LoadedFixtureInterface::class, $result);
        static::assertEquals($model, $result->getModel());
    }

    public function testLoadFixtureThrowsWhenModelTypeDoesNotMatchFixture(): void
    {
        $fixture = new ClientTestFixture();
        $wrongModel = new ClientTestWrongModel();
        $this->fixtureApi->allows('loadFixture')
            ->andReturn(serialize($wrongModel));

        $this->expectException(FixtureModelMismatchException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Fixture "%s" model of type "%s" returned from API does not match expected type "%s"',
                ClientTestFixture::class,
                ClientTestWrongModel::class,
                ClientTestModel::class
            )
        );

        $this->client->loadFixture('myHandle', $fixture);
    }

    public function testLoadFixtureDispatchesCorrectFixtureLoadEvent(): void
    {
        $fixture = new ClientTestFixture();
        $model = new ClientTestModel();
        $this->fixtureApi->allows('loadFixture')
            ->andReturn(serialize($model));

        $this->eventDispatcher->expects()
            ->dispatch(Mockery::type(FixtureLoadEvent::class))
            ->once()
            ->andReturnUsing(function (FixtureLoadEvent $event) use ($fixture, $model) {
                static::assertSame($this->configuration, $event->getConfiguration());

                $loadedFixturesByModelClass = $event->getLoadedFixturesByModelClass();

                static::assertCount(1, $loadedFixturesByModelClass);
                static::assertArrayHasKey(ClientTestModel::class, $loadedFixturesByModelClass);
                static::assertCount(1, $loadedFixturesByModelClass[ClientTestModel::class]);
                static::assertArrayHasKey('myHandle', $loadedFixturesByModelClass[ClientTestModel::class]);
                $loadedFixture = $loadedFixturesByModelClass[ClientTestModel::class]['myHandle'];
                static::assertInstanceOf(LoadedFixtureInterface::class, $loadedFixture);
                static::assertSame($fixture, $loadedFixture->getFixture());
                static::assertEquals($model, $loadedFixture->getModel());
            });

        $this->client->loadFixture('myHandle', $fixture);
    }

    public function testLoadMultipleFixturesCallsFixtureApiWithSerialisedFixtures(): void
    {
        $fixture1 = new ClientTestFixture();
        $fixture2 = new ClientTestFixture2();
        $model1 = new ClientTestModel();
        $model2 = new ClientTestModel2();

        $this->fixtureApi->expects()
            ->loadMultipleFixtures(serialize(['handle1' => $fixture1, 'handle2' => $fixture2]))
            ->once()
            ->andReturn(serialize(['handle1' => $model1, 'handle2' => $model2]));

        $this->client->loadMultipleFixtures(['handle1' => $fixture1, 'handle2' => $fixture2]);
    }

    public function testLoadMultipleFixturesThrowsWhenModelTypeDoesNotMatchFixture(): void
    {
        $fixture1 = new ClientTestFixture();
        $fixture2 = new ClientTestFixture2();
        $model1 = new ClientTestModel();
        $wrongModel = new ClientTestWrongModel();
        $this->fixtureApi->allows('loadMultipleFixtures')->andReturn(
            serialize(['handle1' => $model1, 'handle2' => $wrongModel])
        );

        $this->expectException(FixtureModelMismatchException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Fixture "%s" model of type "%s" with handle "%s" returned from API does not match expected type "%s"',
                ClientTestFixture2::class,
                ClientTestWrongModel::class,
                'handle2',
                ClientTestModel2::class
            )
        );

        $this->client->loadMultipleFixtures(['handle1' => $fixture1, 'handle2' => $fixture2]);
    }

    public function testLoadMultipleFixturesReturnsLoadedFixturesByModelClass(): void
    {
        $fixture1 = new ClientTestFixture();
        $fixture2 = new ClientTestFixture2();
        $model1 = new ClientTestModel();
        $model2 = new ClientTestModel2();
        $this->fixtureApi->allows('loadMultipleFixtures')->andReturn(
            serialize(['handle1' => $model1, 'handle2' => $model2])
        );

        $result = $this->client->loadMultipleFixtures(['handle1' => $fixture1, 'handle2' => $fixture2]);

        static::assertArrayHasKey(ClientTestModel::class, $result);
        static::assertArrayHasKey('handle1', $result[ClientTestModel::class]);
        static::assertEquals($model1, $result[ClientTestModel::class]['handle1']->getModel());
        static::assertArrayHasKey(ClientTestModel2::class, $result);
        static::assertArrayHasKey('handle2', $result[ClientTestModel2::class]);
        static::assertEquals($model2, $result[ClientTestModel2::class]['handle2']->getModel());
    }

    public function testLoadMultipleFixturesDispatchesCorrectFixtureLoadEvent(): void
    {
        $fixture1 = new ClientTestFixture();
        $model1 = new ClientTestModel();
        $this->fixtureApi->allows('loadMultipleFixtures')->andReturn(
            serialize(['handle1' => $model1])
        );

        $this->eventDispatcher->expects()
            ->dispatch(Mockery::type(FixtureLoadEvent::class))
            ->once()
            ->andReturnUsing(function (FixtureLoadEvent $event) use ($fixture1, $model1) {
                static::assertSame($this->configuration, $event->getConfiguration());

                $loadedFixturesByModelClass = $event->getLoadedFixturesByModelClass();

                static::assertCount(1, $loadedFixturesByModelClass);
                static::assertArrayHasKey(ClientTestModel::class, $loadedFixturesByModelClass);
                static::assertCount(1, $loadedFixturesByModelClass[ClientTestModel::class]);
                static::assertArrayHasKey('handle1', $loadedFixturesByModelClass[ClientTestModel::class]);
                $loadedFixture = $loadedFixturesByModelClass[ClientTestModel::class]['handle1'];
                static::assertInstanceOf(LoadedFixtureInterface::class, $loadedFixture);
                static::assertSame($fixture1, $loadedFixture->getFixture());
                static::assertEquals($model1, $loadedFixture->getModel());
            });

        $this->client->loadMultipleFixtures(['handle1' => $fixture1]);
    }

    public function testPurgeCallsFixtureApiWithModelsInReverseOrder(): void
    {
        $fixture1 = new ClientTestFixture();
        $fixture2 = new ClientTestFixture2();
        $model1 = new ClientTestModel();
        $model2 = new ClientTestModel2();
        $loadedFixture1 = new LoadedFixture($fixture1, $model1, 'handle1');
        $loadedFixture2 = new LoadedFixture($fixture2, $model2, 'handle2');

        $this->fixtureApi->expects()
            ->purge(
                [
                    ['fixture' => serialize($fixture2), 'model' => serialize($model2)],
                    ['fixture' => serialize($fixture1), 'model' => serialize($model1)],
                ],
                []
            )
            ->once();

        $this->client->purge([
            ClientTestModel::class => ['handle1' => $loadedFixture1],
            ClientTestModel2::class => ['handle2' => $loadedFixture2],
        ]);
    }

    public function testPurgeSerializesFixtureAndModel(): void
    {
        $fixture = new ClientTestFixture();
        $model = new ClientTestModel();
        $loadedFixture = new LoadedFixture($fixture, $model, 'handle');

        $this->fixtureApi->expects()
            ->purge(
                [
                    ['fixture' => serialize($fixture), 'model' => serialize($model)],
                ],
                []
            )
            ->once();

        $this->client->purge([
            ClientTestModel::class => ['handle' => $loadedFixture],
        ]);
    }

    public function testPurgeSendsDeferredPurgeFixturesSeparatelyFromNormalOnes(): void
    {
        $fixture1 = new ClientTestFixture();
        $fixture2 = new ClientTestDeferredPurgeFixture();
        $model1 = new ClientTestModel();
        $model2 = new ClientTestModel2();
        $loadedFixture1 = new LoadedFixture($fixture1, $model1, 'handle1');
        $loadedFixture2 = new LoadedFixture($fixture2, $model2, 'handle2');

        $this->fixtureApi->expects()
            ->purge(
                [
                    ['fixture' => serialize($fixture1), 'model' => serialize($model1)],
                ],
                [
                    ['fixture' => serialize($fixture2), 'model' => serialize($model2)],
                ]
            )
            ->once();

        $this->client->purge([
            ClientTestModel::class => ['handle1' => $loadedFixture1],
            ClientTestModel2::class => ['handle2' => $loadedFixture2],
        ]);
    }

    public function testPurgeSendsDeferredPurgeModelsInReverseOrder(): void
    {
        $fixture1 = new ClientTestDeferredPurgeFixture();
        $fixture2 = new ClientTestDeferredPurgeFixture2();
        $model1 = new ClientTestModel();
        $model2 = new ClientTestModel2();
        $loadedFixture1 = new LoadedFixture($fixture1, $model1, 'handle1');
        $loadedFixture2 = new LoadedFixture($fixture2, $model2, 'handle2');

        $this->fixtureApi->expects()
            ->purge(
                [],
                [
                    ['fixture' => serialize($fixture2), 'model' => serialize($model2)],
                    ['fixture' => serialize($fixture1), 'model' => serialize($model1)],
                ]
            )
            ->once();

        $this->client->purge([
            ClientTestModel::class => ['handle1' => $loadedFixture1],
            ClientTestModel2::class => ['handle2' => $loadedFixture2],
        ]);
    }
}

/**
 * @implements FixtureInterface<ClientTestModel>
 */
class ClientTestFixture implements FixtureInterface
{
    public static function getModelClass(): string
    {
        return ClientTestModel::class;
    }
}

/**
 * @implements FixtureInterface<ClientTestModel2>
 */
class ClientTestFixture2 implements FixtureInterface
{
    public static function getModelClass(): string
    {
        return ClientTestModel2::class;
    }
}

/**
 * @implements FixtureInterface<ClientTestModel>
 */
class ClientTestDeferredPurgeFixture implements DeferredPurgeFixtureInterface, FixtureInterface
{
    public static function getModelClass(): string
    {
        return ClientTestModel::class;
    }
}

/**
 * @implements FixtureInterface<ClientTestModel2>
 */
class ClientTestDeferredPurgeFixture2 implements DeferredPurgeFixtureInterface, FixtureInterface
{
    public static function getModelClass(): string
    {
        return ClientTestModel2::class;
    }
}

class ClientTestModel implements ModelInterface
{
}

class ClientTestModel2 implements ModelInterface
{
}

class ClientTestWrongModel implements ModelInterface
{
}
