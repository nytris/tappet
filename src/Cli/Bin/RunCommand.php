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

namespace Tappet\Cli\Bin;

use Tappet\Cli\Config\ConfigInterface;
use Tappet\Cli\Environment\EnvironmentInterface;
use Tappet\Cli\Io\OutputInterface;
use Tappet\Core\Exception\ConfigurationExceptionInterface;
use Tappet\Core\Exception\ExceptionInterface;
use Tappet\Core\Exception\MissingApiBaseUrlException;
use Tappet\Core\Exception\MissingApiKeyException;
use Tappet\Core\Exception\MissingBaseUrlException;
use Tappet\Suite\Cli\CliOptionInterface;
use Tappet\Suite\Cli\CliSpecInterface;
use Tappet\Suite\Result\ResultInterface;
use Tappet\Suite\SuiteInterface;
use Tappet\Suite\SuiteResolverInterface;

/**
 * Class RunCommand.
 *
 * `tappet run` command entrypoint.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RunCommand implements RunCommandInterface
{
    /**
     * Options that are always valid regardless of the suite's CLI spec.
     */
    private const GLOBAL_OPTION_NAMES = ['api-base-url', 'api-key', 'base-url', 'filter', 'help', 'project'];

    /**
     * @param SuiteResolverInterface<SuiteInterface> $suiteResolver
     */
    public function __construct(
        private readonly ConfigInterface $config,
        private readonly SuiteResolverInterface $suiteResolver,
        private readonly OutputInterface $stdout,
        private readonly OutputInterface $stderr,
        private readonly string $projectRoot,
        private readonly EnvironmentInterface $environment
    ) {
    }

    /**
     * @inheritDoc
     */
    public function help(?string $suiteName): int
    {
        $resolvedSuiteName = $suiteName ?? $this->config->getDefaultSuite();

        if ($resolvedSuiteName !== null) {
            if (!$this->config->isPresent()) {
                $this->stderr->write($this->formatMissingConfigError());

                return 1;
            }

            try {
                $suite = $this->suiteResolver->resolveSuite($resolvedSuiteName);
                $spec = $suite->getCliSpec();

                $this->stderr->write($this->formatSuiteHelp($resolvedSuiteName, $spec));

                return 0;
            } catch (ConfigurationExceptionInterface $exception) {
                $this->stderr->write($exception->getMessage() . PHP_EOL);

                return 1;
            }
        }

        $this->stderr->write($this->formatGenericRunHelp());

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function run(?string $suiteName, array $options): ?ResultInterface
    {
        if (!$this->config->isPresent()) {
            $this->stderr->write($this->formatMissingConfigError());

            return null;
        }

        $resolvedSuiteName = $suiteName ?? $this->config->getDefaultSuite();

        if ($resolvedSuiteName === null) {
            $this->stderr->write(
                'Error: no suite name specified and no default suite configured.' . PHP_EOL
            );

            return null;
        }

        try {
            $suite = $this->suiteResolver->resolveSuite($resolvedSuiteName);
        } catch (ConfigurationExceptionInterface $exception) {
            $this->stderr->write($exception->getMessage() . PHP_EOL);

            return null;
        }

        $spec = $suite->getCliSpec();
        $specOptionNames = array_map(
            static fn (CliOptionInterface $option) => $option->getName(),
            $spec->getOptions()
        );
        $allowedOptionNames = array_merge(self::GLOBAL_OPTION_NAMES, $specOptionNames);

        foreach (array_keys($options) as $optionName) {
            if (!in_array($optionName, $allowedOptionNames, true)) {
                $this->stderr->write('Error: unrecognised option "--' . $optionName . '".' . PHP_EOL);

                return null;
            }
        }

        $baseUrl = (string) ($options['base-url'] ?? $this->environment->getEnvironmentVariable('TAPPET_BASE_URL') ?? $this->config->getDefaultBaseUrl() ?? '');
        $apiBaseUrl = (string) ($options['api-base-url'] ?? $this->environment->getEnvironmentVariable('TAPPET_API_BASE_URL') ?? $this->config->getDefaultApiBaseUrl() ?? '');
        $apiKey = (string) ($options['api-key'] ?? $this->environment->getEnvironmentVariable('TAPPET_API_KEY') ?? $this->config->getDefaultApiKey() ?? '');
        $filter = isset($options['filter']) ? (string) $options['filter'] : $this->config->getDefaultFilter();

        // Tidy up $options: it should only pass through custom options that the suite expects, as defined by its CliSpec.
        unset($options['api-base-url'], $options['api-key'], $options['base-url'], $options['filter']);

        try {
            if ($baseUrl === '') {
                throw new MissingBaseUrlException(
                    'Error: no base URL specified. Provide one via --base-url or the TAPPET_BASE_URL environment variable.'
                );
            }

            if ($apiBaseUrl === '') {
                throw new MissingApiBaseUrlException(
                    'Error: no API base URL specified. Provide one via --api-base-url or the TAPPET_API_BASE_URL environment variable.'
                );
            }

            if ($apiKey === '') {
                throw new MissingApiKeyException(
                    'Error: no API key specified. Provide one via --api-key or the TAPPET_API_KEY environment variable.'
                );
            }

            // Run the test suite.
            $result = $suite->run($this->projectRoot, $resolvedSuiteName, $baseUrl, $apiBaseUrl, $apiKey, $filter, $options);
        } catch (ExceptionInterface $exception) {
            $this->stderr->write($exception->getMessage() . PHP_EOL);

            return null;
        }

        // Print the output.
        $this->stdout->write($result->getOutput());

        return $result;
    }

    private function formatGenericRunHelp(): string
    {
        return <<<HELP
Usage: tappet run [<suite-name>] [options]

Run 'tappet run <suite-name> --help' for suite-specific options.

Global options:
  --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
  --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
  --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
  --filter <pattern>      Filter tests by name pattern.

HELP;
    }

    private function formatMissingConfigError(): string
    {
        return 'Error: Tappet config file (tappet.config.php) not found. Ensure it exists in your project root, or use --project to specify a path.' . PHP_EOL;
    }

    private function formatSuiteHelp(string $suiteName, CliSpecInterface $spec): string
    {
        $lines = [];
        $lines[] = 'Usage: tappet run ' . $suiteName . ' [options]';
        $lines[] = '';

        $suiteOptions = $spec->getOptions();

        if (count($suiteOptions) > 0) {
            $lines[] = 'Suite "' . $suiteName . '" options:';

            foreach ($suiteOptions as $option) {
                $flag = '--' . $option->getName();

                if ($option->isValueExpected()) {
                    $flag .= ' <value>';
                }

                $suffix = $option->isRequired() ? ' (required)' : '';
                $lines[] = sprintf('  %-24s%s%s', $flag, $option->getDescription(), $suffix);
            }
        } else {
            $lines[] = 'Suite "' . $suiteName . '" declares no additional options.';
        }

        $lines[] = '';
        $lines[] = 'Global options:';
        $lines[] = sprintf('  %-24s%s', '--api-base-url <url>', 'Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).');
        $lines[] = sprintf('  %-24s%s', '--api-key <key>', 'Tappet API key (or TAPPET_API_KEY env var).');
        $lines[] = sprintf('  %-24s%s', '--base-url <url>', 'Base URL of the GUI application under test (or TAPPET_BASE_URL env var).');
        $lines[] = sprintf('  %-24s%s', '--filter <pattern>', 'Filter tests by name pattern.');
        $lines[] = '';

        return implode(PHP_EOL, $lines);
    }
}
