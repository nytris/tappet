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

namespace Tappet\Suite;

use Tappet\Common\Exception\ExceptionInterface;
use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Action\InteractionInterface;
use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Assertion\RegionAssertionInterface;
use Tappet\Runner\Assertion\StateAssertionInterface;
use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Runner\Automation\Field\FieldActionHandlerInterface;
use Tappet\Runner\Automation\Field\FieldAssertionHandlerInterface;
use Tappet\Runner\Automation\Interaction\InteractionHandlerInterface;
use Tappet\Runner\Automation\Matcher\MatchHandlerInterface;
use Tappet\Runner\Automation\Region\RegionAssertionHandlerInterface;
use Tappet\Runner\Automation\State\StateAssertionHandlerInterface;
use Tappet\Runner\Matcher\ContextInterface;
use Tappet\Runner\Matcher\MatcherInterface;
use Tappet\Suite\Cli\CliSpecInterface;
use Tappet\Suite\Plugin\PluginInterface;
use Tappet\Suite\Result\ResultInterface;

/**
 * Interface SuiteInterface.
 *
 * Test suite configuration for Tappet, implemented by adapter suites, allowing the suite implementation
 * to be configured via `tappet.{suite-name}.config.php`.
 *
 * For example, a CypressSuite would implement this interface to allow defining test suites that use Cypress.
 *
 * @template TAutomation of AutomationInterface
 * @template TContext of ContextInterface = ContextInterface
 * @author Dan Phillimore <dan@ovms.co>
 */
interface SuiteInterface
{
    /**
     * Adds a plugin to extend the suite's behaviour.
     *
     * @param PluginInterface<TAutomation> $plugin
     */
    public function addPlugin(PluginInterface $plugin): void;

    /**
     * Returns a descriptor of the CLI options that this suite supports,
     * used for help display and validation of (un)recognised options.
     */
    public function getCliSpec(): CliSpecInterface;

    /**
     * Registers a handler for the given field type.
     *
     * @param FieldActionHandlerInterface<TAutomation, FieldActionInterface> $handler
     */
    public function registerFieldActionHandler(string $fieldType, FieldActionHandlerInterface $handler): void;

    /**
     * Registers a handler for assertions of the given field type.
     *
     * @param FieldAssertionHandlerInterface<TAutomation, FieldAssertionInterface> $handler
     */
    public function registerFieldAssertionHandler(string $fieldType, FieldAssertionHandlerInterface $handler): void;

    /**
     * Registers a handler for the given interaction type.
     *
     * @param InteractionHandlerInterface<TAutomation, InteractionInterface> $handler
     */
    public function registerInteractionHandler(string $interactionType, InteractionHandlerInterface $handler): void;

    /**
     * Registers a handler contributing matcher FQCN(s) (e.g. Text, ExactText) for the given matcher type.
     * Note that the type will almost always be "default", unless overridden for a specific column/list/etc.
     *
     * @param MatchHandlerInterface<TAutomation, MatcherInterface, TContext> $handler
     */
    public function registerMatchHandler(string $matcherType, MatchHandlerInterface $handler): void;

    /**
     * Registers a handler for the given region type.
     *
     * @param RegionAssertionHandlerInterface<TAutomation, RegionAssertionInterface> $handler
     */
    public function registerRegionAssertionHandler(string $regionType, RegionAssertionHandlerInterface $handler): void;

    /**
     * Registers a handler for the given state type.
     *
     * @param StateAssertionHandlerInterface<TAutomation, StateAssertionInterface> $handler
     */
    public function registerStateAssertionHandler(string $stateType, StateAssertionHandlerInterface $handler): void;

    /**
     * Runs the test suite.
     *
     * @param string $projectRoot The root of the project.
     * @param string $suiteName The name of the suite to run.
     * @param string $baseUrl The base URL of the GUI application under test.
     * @param string $apiBaseUrl The base URL of the Tappet API.
     * @param string $apiKey The API key to authenticate with.
     * @param string|null $filter An optional filter to run only matching tests.
     * @param array<string, mixed> $options CLI options.
     * @throws ExceptionInterface When an error occurs during the run.
     */
    public function run(
        string $projectRoot,
        string $suiteName,
        string $baseUrl,
        string $apiBaseUrl,
        string $apiKey,
        ?string $filter,
        array $options
    ): ResultInterface;
}
