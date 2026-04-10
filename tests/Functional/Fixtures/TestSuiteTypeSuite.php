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

use Tappet\Core\Automation\Field\FieldActionHandlerInterface;
use Tappet\Core\Automation\Interaction\InteractionHandlerInterface;
use Tappet\Core\Automation\Region\RegionAssertionHandlerInterface;
use Tappet\Core\Automation\State\StateAssertionHandlerInterface;
use Tappet\Suite\Cli\CliOption;
use Tappet\Suite\Cli\CliSpec;
use Tappet\Suite\Cli\CliSpecInterface;
use Tappet\Suite\Result\ResultInterface;
use Tappet\Suite\Result\TestResult;
use Tappet\Suite\SuiteInterface;

/**
 * Class TestSuiteTypeSuite.
 *
 * Stub implementation of SuiteInterface for use in functional tests.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestSuiteTypeSuite implements SuiteInterface
{
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
    public function registerFieldActionHandler(string $fieldType, FieldActionHandlerInterface $handler): void
    {
    }

    /**
     * @inheritDoc
     */
    public function registerInteractionHandler(string $interactionType, InteractionHandlerInterface $handler): void
    {
    }

    /**
     * @inheritDoc
     */
    public function registerRegionAssertionHandler(string $regionType, RegionAssertionHandlerInterface $handler): void
    {
    }

    /**
     * @inheritDoc
     */
    public function registerStateAssertionHandler(string $stateType, StateAssertionHandlerInterface $handler): void
    {
    }

    /**
     * @inheritDoc
     */
    public function run(string $projectRoot, string $suiteName, string $apiBaseUrl, string $apiKey, array $options): ResultInterface
    {
        $output = 'Test suite "' . $suiteName . '" output.';

        if ($apiBaseUrl !== '') {
            $output .= ' API base URL: "' . $apiBaseUrl . '".';
        }

        if ($apiKey !== '') {
            $output .= ' API key: "' . $apiKey . '".';
        }

        return new TestResult($output, false);
    }
}
