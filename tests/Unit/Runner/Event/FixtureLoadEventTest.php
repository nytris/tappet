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

namespace Tappet\Tests\Unit\Runner\Event;

use Mockery\MockInterface;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Configuration\ConfigurationInterface;
use Tappet\Runner\Event\FixtureLoadEvent;
use Tappet\Runner\Fixture\LoadedFixture;
use Tappet\Runner\Fixture\LoadedFixtureInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class FixtureLoadEventTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FixtureLoadEventTest extends AbstractTestCase
{
    private ConfigurationInterface&MockInterface $configuration;
    /**
     * @var FixtureLoadEvent
     */
    private FixtureLoadEvent $event;
    /**
     * @var FixtureInterface<ModelInterface>
     */
    private FixtureInterface $fixture;
    /**
     * @var LoadedFixtureInterface<FixtureInterface<ModelInterface>, ModelInterface>
     */
    private LoadedFixtureInterface $loadedFixture;
    private MockInterface&ModelInterface $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->configuration = mock(ConfigurationInterface::class);
        $this->fixture = mock(FixtureLoadEventTestFixture::class);
        $this->model = mock(FixtureLoadEventTestModel::class);
        $this->loadedFixture = new LoadedFixture($this->fixture, $this->model, 'myHandle');

        $this->event = new FixtureLoadEvent(
            [
                FixtureLoadEventTestModel::class => ['myHandle' => $this->loadedFixture]
            ],
            $this->configuration
        );
    }

    public function testGetConfigurationReturnsConfiguration(): void
    {
        static::assertSame($this->configuration, $this->event->getConfiguration());
    }

    public function testGetFixtureModelsReturnsModelsForGivenModelClass(): void
    {
        $result = $this->event->getFixtureModels(FixtureLoadEventTestModel::class);

        static::assertCount(1, $result);
        static::assertArrayHasKey('myHandle', $result);
        static::assertSame($this->model, $result['myHandle']);
    }

    public function testGetFixtureModelsReturnsEmptyArrayWhenNoModelsForModelClass(): void
    {
        $result = $this->event->getFixtureModels(FixtureLoadEventTestOtherModel::class);

        static::assertSame([], $result);
    }

    public function testGetLoadedFixturesByModelClassReturnsAllLoadedFixtures(): void
    {
        $result = $this->event->getLoadedFixturesByModelClass();

        static::assertArrayHasKey(FixtureLoadEventTestModel::class, $result);
        static::assertSame($this->loadedFixture, $result[FixtureLoadEventTestModel::class]['myHandle']);
    }
}

/**
 * @implements FixtureInterface<FixtureLoadEventTestModel>
 */
class FixtureLoadEventTestFixture implements FixtureInterface
{
    public static function getModelClass(): string
    {
        return FixtureLoadEventTestModel::class;
    }
}

class FixtureLoadEventTestModel implements ModelInterface
{
}

class FixtureLoadEventTestOtherModel implements ModelInterface
{
}
