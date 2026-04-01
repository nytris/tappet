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

use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Assertion\StateAssertionInterface;
use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Fixture\ModelRepositoryInterface;

/**
 * Class Environment.
 *
 * Represents the test environment provided to test components.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Environment implements EnvironmentInterface
{
    /**
     * @var AutomationInterface
     */
    private $automation;
    /**
     * @var string
     */
    private $baseUrl;
    /**
     * @var ModelRepositoryInterface
     */
    private $modelRepository;

    public function __construct(
        ModelRepositoryInterface $modelRepository,
        AutomationInterface $automation,
        string $baseUrl
    ) {
        $this->automation = $automation;
        $this->baseUrl = $baseUrl;
        $this->modelRepository = $modelRepository;
    }

    /**
     * @inheritDoc
     */
    public function assertPage(string $url): void
    {
        $this->automation->assertPage($url, $this);
    }

    /**
     * @inheritDoc
     */
    public function getAutomation(): AutomationInterface
    {
        return $this->automation;
    }

    /**
     * @inheritDoc
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @inheritDoc
     */
    public function getFixtureModel(string $modelClass, string $handle): ModelInterface
    {
        return $this->modelRepository->getFixtureModel($modelClass, $handle);
    }

    /**
     * @inheritDoc
     */
    public function loadFixture(string $handle, FixtureInterface $fixture): void
    {
        $this->modelRepository->loadFixture($handle, $fixture);
    }

    /**
     * @inheritDoc
     */
    public function loadMultipleFixtures(array $fixtures): void
    {
        $this->modelRepository->loadMultipleFixtures($fixtures);
    }

    /**
     * @inheritDoc
     */
    public function performFieldAction(FieldActionInterface $action): void
    {
        $this->automation->performFieldAction($action);
    }

    /**
     * @inheritDoc
     */
    public function performInteraction(InteractionInterface $interaction): void
    {
        $this->automation->performInteraction($interaction);
    }

    /**
     * @inheritDoc
     */
    public function performRegionAssertion(RegionAssertionInterface $assertion): void
    {
        $this->automation->performRegionAssertion($assertion);
    }

    /**
     * @inheritDoc
     */
    public function performStateAssertion(StateAssertionInterface $assertion): void
    {
        $this->automation->performStateAssertion($assertion);
    }

    /**
     * @inheritDoc
     */
    public function visitPage(string $url): void
    {
        $this->automation->visitPage($url);
    }
}
