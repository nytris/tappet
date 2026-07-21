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
use Tappet\Runner\Automation\AutomationInterface;
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
