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

namespace Tappet\Runner\Environment;

use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Assertion\RegionAssertionInterface;
use Tappet\Runner\Assertion\StateAssertionInterface;
use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Runner\Configuration\ConfigurationInterface;
use Tappet\Runner\Fixture\ModelRepositoryInterface;
use Tappet\Runner\Page\PageInterface;
use Tappet\Runner\Transition\NavigationTransition;
use Tappet\Runner\Transition\TransitionInterface;

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
     * The last explicitly declared expected transition, set by ->visitPage()
     * and ->assertTransition(). Represents a one-time expected match: the first
     * implicit check will attempt to consume this transition from the log (consuming
     * it if present, or failing if something unexpected appeared instead). After that
     * first check, this is cleared so that any further log entries are treated as
     * unexpected.
     */
    private TransitionInterface|null $currentTransition = null;

    public function __construct(
        private readonly ModelRepositoryInterface $modelRepository,
        private readonly AutomationInterface $automation,
        private readonly ConfigurationInterface $configuration
    ) {
    }

    /**
     * Checks the transition log for unexpected entries since the last assertion.
     * Called before every non-declaring step to detect unexpected whole-page-state changes.
     *
     * If a pending expected transition is set, attempts to consume it from the log (one-time
     * match), then clears it so that any subsequent new entries trigger a failure.
     * If no expected transition is pending, any new log entry is treated as unexpected.
     */
    private function assertCurrentTransition(): void
    {
        if ($this->currentTransition !== null) {
            $transition = $this->currentTransition;
            // Clear before the check so that if it is checked again (with no new transition)
            // it falls through to assertTransitionLogEmpty(), catching unexpected new entries.
            $this->currentTransition = null;
            $this->automation->waitForTransition($transition);
        } else {
            $this->automation->assertTransitionLogEmpty();
        }
    }

    /**
     * @inheritDoc
     */
    public function assertTransition(TransitionInterface $transition): void
    {
        $this->currentTransition = null;

        $this->automation->waitForTransition($transition);
    }

    /**
     * @inheritDoc
     */
    public function assertTransitionLogEmpty(): void
    {
        $this->automation->assertTransitionLogEmpty();
    }

    /**
     * @inheritDoc
     */
    public function buildFullyQualifiedUrl(string $url): string
    {
        if ($url[0] === '/') {
            $url = $this->configuration->getBaseUrl() . $url;
        }

        return $url;
    }

    /**
     * @inheritDoc
     */
    public function buildPageUrl(PageInterface $page): string
    {
        return $this->buildFullyQualifiedUrl($page->buildUrl($this));
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
        return $this->configuration->getBaseUrl();
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
        $this->assertCurrentTransition();

        $this->automation->performFieldAction($action);
    }

    /**
     * @inheritDoc
     */
    public function performFieldAssertion(FieldAssertionInterface $assertion): void
    {
        $this->assertCurrentTransition();

        $this->automation->performFieldAssertion($assertion);
    }

    /**
     * @inheritDoc
     */
    public function performInteraction(InteractionInterface $interaction): void
    {
        $this->assertCurrentTransition();

        $this->automation->performInteraction($interaction);
    }

    /**
     * @inheritDoc
     */
    public function performRegionAssertion(RegionAssertionInterface $assertion): void
    {
        $this->assertCurrentTransition();

        $this->automation->performRegionAssertion($assertion);
    }

    /**
     * @inheritDoc
     */
    public function performStateAssertion(StateAssertionInterface $assertion): void
    {
        $this->assertCurrentTransition();

        $this->automation->performStateAssertion($assertion);
    }

    /**
     * @inheritDoc
     */
    public function visitPage(PageInterface $page): void
    {
        $this->visitUrl($page->buildUrl($this));
    }

    /**
     * @inheritDoc
     */
    public function visitUrl(string $url): void
    {
        $this->assertCurrentTransition();

        $visitUrl = $this->buildFullyQualifiedUrl($url);

        $this->currentTransition = new NavigationTransition($visitUrl);
        $this->automation->visitPage($visitUrl);
    }
}
