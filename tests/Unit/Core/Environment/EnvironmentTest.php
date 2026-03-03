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

namespace Tappet\Tests\Unit\Core\Environment;

use Mockery\MockInterface;
use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Environment\Environment;
use Tappet\Core\Environment\Field\Field;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Fixture\ModelRepositoryInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class EnvironmentTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EnvironmentTest extends AbstractTestCase
{
    private AutomationInterface&MockInterface $automation;
    private Environment $environment;
    private ModelRepositoryInterface&MockInterface $modelRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = mock(AutomationInterface::class);
        $this->modelRepository = mock(ModelRepositoryInterface::class);

        $this->environment = new Environment($this->modelRepository, $this->automation);
    }

    public function testAssertPageDelegatesToAutomation(): void
    {
        $this->automation->expects()->assertPage('https://example.com/dashboard')
            ->once();

        $this->environment->assertPage('https://example.com/dashboard');
    }

    public function testGetFieldReturnsFieldInstance(): void
    {
        $field = $this->environment->getField('username');

        static::assertInstanceOf(Field::class, $field);
    }

    public function testGetFieldReturnsFieldWithCorrectHandle(): void
    {
        $field = $this->environment->getField('username');

        static::assertSame('username', $field->getHandle());
    }

    public function testGetFieldReturnsDifferentFieldForDifferentHandle(): void
    {
        $field = $this->environment->getField('password');

        static::assertSame('password', $field->getHandle());
    }

    public function testGetFixtureModelDelegatesToModelRepository(): void
    {
        $model = mock(ModelInterface::class);

        $this->modelRepository->expects()
            ->getFixtureModel(ModelInterface::class, 'myHandle')
            ->once()
            ->andReturn($model);

        $result = $this->environment->getFixtureModel(ModelInterface::class, 'myHandle');

        static::assertSame($model, $result);
    }

    public function testLoadFixtureDelegatesToModelRepository(): void
    {
        $fixture = mock(FixtureInterface::class);

        $this->modelRepository->expects()
            ->loadFixture('myHandle', $fixture)
            ->once();

        $this->environment->loadFixture('myHandle', $fixture);
    }

    public function testVisitPageDelegatesToAutomation(): void
    {
        $this->automation->expects()
            ->visitPage('https://example.com/login')
            ->once();

        $this->environment->visitPage('https://example.com/login');
    }
}
