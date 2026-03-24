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

namespace Tappet\Core\Environment;

use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Environment\Field\Field;
use Tappet\Core\Environment\Field\FieldInterface;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Fixture\ModelRepositoryInterface;

class Environment implements EnvironmentInterface
{
    /**
     * @var AutomationInterface
     */
    private $automation;
    /**
     * @var ModelRepositoryInterface
     */
    private $modelRepository;

    public function __construct(ModelRepositoryInterface $modelRepository, AutomationInterface $automation)
    {
        $this->automation = $automation;
        $this->modelRepository = $modelRepository;
    }

    public function assertPage(string $url): void
    {
        $this->automation->assertPage($url);
    }

    public function getField(string $handle): FieldInterface
    {
        return new Field($this->automation, $handle);
    }

    public function getFixtureModel(string $modelClass, string $handle): ModelInterface
    {
        return $this->modelRepository->getFixtureModel($modelClass, $handle);
    }

    /**
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): void
    {
        $this->modelRepository->loadFixture($handle, $fixture);
    }

    public function visitPage(string $url): void
    {
        $this->automation->visitPage($url);
    }
}
