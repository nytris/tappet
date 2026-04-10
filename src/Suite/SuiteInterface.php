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

use Tappet\Core\Automation\Field\FieldActionHandlerInterface;
use Tappet\Core\Automation\Interaction\InteractionHandlerInterface;
use Tappet\Core\Automation\Region\RegionAssertionHandlerInterface;
use Tappet\Core\Automation\State\StateAssertionHandlerInterface;
use Tappet\Core\Exception\ExceptionInterface;
use Tappet\Suite\Cli\CliSpecInterface;
use Tappet\Suite\Result\ResultInterface;

/**
 * Interface SuiteInterface.
 *
 * Test suite configuration for Tappet, implemented by adapter suites, allowing the suite implementation
 * to be configured via `tappet.{suite-name}.config.php`.
 *
 * For example, a CypressSuite would implement this interface to allow defining test suites that use Cypress.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface SuiteInterface
{
    /**
     * Returns a descriptor of the CLI options that this suite supports,
     * used for help display and validation of (un)recognised options.
     */
    public function getCliSpec(): CliSpecInterface;

    /**
     * Registers a handler for the given field type.
     */
    public function registerFieldActionHandler(string $fieldType, FieldActionHandlerInterface $handler): void;

    /**
     * Registers a handler for the given interaction type.
     */
    public function registerInteractionHandler(string $interactionType, InteractionHandlerInterface $handler): void;

    /**
     * Registers a handler for the given region type.
     */
    public function registerRegionAssertionHandler(string $regionType, RegionAssertionHandlerInterface $handler): void;

    /**
     * Registers a handler for the given state type.
     */
    public function registerStateAssertionHandler(string $stateType, StateAssertionHandlerInterface $handler): void;

    /**
     * Runs the test suite.
     *
     * @param string $projectRoot The root of the project.
     * @param string $suiteName The name of the suite to run.
     * @param string $apiBaseUrl The base URL of the Tappet API.
     * @param string $apiKey The API key to authenticate with.
     * @param array<string, mixed> $options CLI options.
     * @throws ExceptionInterface When an error occurs during the run.
     */
    public function run(
        string $projectRoot,
        string $suiteName,
        string $apiBaseUrl,
        string $apiKey,
        array $options
    ): ResultInterface;
}
