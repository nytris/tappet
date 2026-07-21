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

namespace Tappet\Tests\Functional\Fixtures;

use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Suite\Cli\CliOption;
use Tappet\Suite\Cli\CliSpec;
use Tappet\Suite\Cli\CliSpecInterface;
use Tappet\Suite\Plugin\PluginInterface;
use Tappet\Suite\Result\ResultInterface;
use Tappet\Suite\Result\TestResult;
use Tappet\Suite\SuiteInterface;

/**
 * Class TestSuiteTypeSuite.
 *
 * Stub implementation of SuiteInterface for use in functional tests.
 *
 * @implements SuiteInterface<AutomationInterface>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestSuiteTypeSuite implements SuiteInterface
{
    /**
     * @inheritDoc
     */
    public function addPlugin(PluginInterface $plugin): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getCliSpec(): CliSpecInterface
    {
        return new CliSpec([
            new CliOption('sub-filter', 'Sub-filter tests by name pattern.'),
        ]);
    }

    /**
     * @inheritDoc
     */
    public function run(string $projectRoot, string $suiteName, string $baseUrl, string $apiBaseUrl, string $apiKey, ?string $filter, array $options): ResultInterface
    {
        $output = 'Test suite "' . $suiteName . '" output.';

        if ($baseUrl !== '') {
            $output .= ' Base URL: "' . $baseUrl . '".';
        }

        if ($apiBaseUrl !== '') {
            $output .= ' API base URL: "' . $apiBaseUrl . '".';
        }

        if ($apiKey !== '') {
            $output .= ' API key: "' . $apiKey . '".';
        }

        if ($filter !== null) {
            $output .= ' Filter: "' . $filter . '".';
        }

        return new TestResult($output, false);
    }
}
