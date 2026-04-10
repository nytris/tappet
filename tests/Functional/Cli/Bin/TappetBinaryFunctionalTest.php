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

namespace Tappet\Tests\Functional\Cli\Bin;

use Composer\InstalledVersions;
use Tappet\Cli\Bin\CliParser;
use Tappet\Cli\Bin\RunCommand;
use Tappet\Cli\Bin\TappetBinary;
use Tappet\Cli\Config\ConfigResolver;
use Tappet\Cli\Io\RecordingOutput;
use Tappet\Suite\SuiteInterface;
use Tappet\Suite\SuiteResolver;
use Tappet\Tests\Functional\AbstractFunctionalTestCase;
use Tappet\Tests\Functional\Fixtures\TestEnvironment;

/**
 * Class TappetBinaryFunctionalTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TappetBinaryFunctionalTest extends AbstractFunctionalTestCase
{
    private CliParser $cliParser;
    private TestEnvironment $environment;
    private string $fixturesPath;
    private RecordingOutput $recordingStderr;
    private RecordingOutput $recordingStdout;
    private TappetBinary $tappetBinary;

    public function setUp(): void
    {
        parent::setUp();

        $this->cliParser = new CliParser();
        $this->environment = new TestEnvironment([
            'TAPPET_API_BASE_URL' => 'https://default.example.com',
            'TAPPET_API_KEY' => 'default-test-key',
        ]);
        $this->fixturesPath = __DIR__ . '/../../Fixtures/TappetConfiguredApp';
        $this->recordingStdout = new RecordingOutput();
        $this->recordingStderr = new RecordingOutput();

        $configResolver = new ConfigResolver($this->fixturesPath);
        $config = $configResolver->resolveConfig();

        $suiteResolver = new SuiteResolver(SuiteInterface::class, [$this->fixturesPath]);
        $runCommand = new RunCommand(
            $config,
            $suiteResolver,
            $this->recordingStdout,
            $this->recordingStderr,
            '/my/project/root',
            $this->environment
        );

        $this->tappetBinary = new TappetBinary($runCommand, $this->recordingStderr);
    }

    public function testRunWithNoArgsUsesDefaultSuiteAndReturnsExitCodeZeroOnSuccess(): void
    {
        // The fixture tappet.config.php sets the default suite to 'mysuite',
        // which is backed by tappet.mysuite.suite.php returning a TestSuiteTypeSuite.
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://default.example.com". API key: "default-test-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunWithExplicitSuiteNameRunsCorrectSuite(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://default.example.com". API key: "default-test-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunWithRunCommandAndNoSuiteNameUsesDefaultSuite(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://default.example.com". API key: "default-test-key".', $this->recordingStdout->getOutput());
    }

    public function testRunWithNoApiBaseUrlWritesErrorToStderrAndReturnsExitCodeOne(): void
    {
        $this->environment->unsetVariable('TAPPET_API_BASE_URL');

        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite']));

        static::assertSame(1, $exitCode);
        static::assertSame(
            'Error: no API base URL specified. Provide one via --api-base-url or the TAPPET_API_BASE_URL environment variable.' . PHP_EOL,
            $this->recordingStderr->getOutput()
        );
    }

    public function testRunWithNoApiKeyWritesErrorToStderrAndReturnsExitCodeOne(): void
    {
        $this->environment->unsetVariable('TAPPET_API_KEY');

        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite']));

        static::assertSame(1, $exitCode);
        static::assertSame(
            'Error: no API key specified. Provide one via --api-key or the TAPPET_API_KEY environment variable.' . PHP_EOL,
            $this->recordingStderr->getOutput()
        );
    }

    public function testRunPassesApiKeyOptionToSuite(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite', '--api-key', 'my-secret-key']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://default.example.com". API key: "my-secret-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunPassesApiKeyEnvironmentVariableToSuite(): void
    {
        $this->environment->setVariable('TAPPET_API_KEY', 'env-secret-key');

        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://default.example.com". API key: "env-secret-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunApiKeyOptionTakesPrecedenceOverEnvironmentVariable(): void
    {
        $this->environment->setVariable('TAPPET_API_KEY', 'env-secret-key');

        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite', '--api-key', 'option-key']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://default.example.com". API key: "option-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunPassesApiBaseUrlOptionToSuite(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite', '--api-base-url', 'https://custom.example.com']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://custom.example.com". API key: "default-test-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunPassesApiBaseUrlEnvironmentVariableToSuite(): void
    {
        $this->environment->setVariable('TAPPET_API_BASE_URL', 'https://env.example.com');

        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://env.example.com". API key: "default-test-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunApiBaseUrlOptionTakesPrecedenceOverEnvironmentVariable(): void
    {
        $this->environment->setVariable('TAPPET_API_BASE_URL', 'https://env.example.com');

        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite', '--api-base-url', 'https://option.example.com']));

        static::assertSame(0, $exitCode);
        static::assertSame('Test suite "mysuite" output. API base URL: "https://option.example.com". API key: "default-test-key".', $this->recordingStdout->getOutput());
        static::assertSame('', $this->recordingStderr->getOutput());
    }

    public function testRunWithMissingSuiteFileWritesErrorToStderrAndReturnsOne(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'nonexistent']));

        static::assertSame(1, $exitCode);
        static::assertSame(
            'Tappet suite config file tappet.nonexistent.suite.php is required but was not found in any of the configured paths: [' . $this->fixturesPath . ']' . PHP_EOL,
            $this->recordingStderr->getOutput()
        );
    }

    public function testRunHelpCommandWritesUsageAndReturnsZero(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'help']));

        static::assertSame(0, $exitCode);
        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet [<command>] [options]

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --project <path>        Path to the project root directory.

            Commands:
              run [<suite-name>]      Run the given suite, or the default one if none is specified.
              help                    Show CLI help and exit.
              version                 Print the Tappet version.

            Run 'tappet run <suite-name> --help' for suite-specific help.

            EXPECTED,
            $this->recordingStderr->getOutput()
        );
    }

    public function testRunVersionCommandWritesVersionAndReturnsExitCodeZero(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'version']));

        $version = InstalledVersions::getPrettyVersion('tappet/tappet') ?? '(version unknown)';
        static::assertSame(0, $exitCode);
        static::assertSame('Nytris Tappet ' . $version . PHP_EOL, $this->recordingStderr->getOutput());
    }

    public function testRunUnknownCommandWritesErrorAndReturnsExitCodeOne(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'unknown-command']));

        static::assertSame(1, $exitCode);
        static::assertSame(
            <<<'EXPECTED'
            Error: Unknown command "unknown-command"

            Usage: tappet [<command>] [options]

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --project <path>        Path to the project root directory.

            Commands:
              run [<suite-name>]      Run the given suite, or the default one if none is specified.
              help                    Show CLI help and exit.
              version                 Print the Tappet version.

            Run 'tappet run <suite-name> --help' for suite-specific help.

            EXPECTED,
            $this->recordingStderr->getOutput()
        );
    }

    public function testRunWithHelpOptionDisplaysSuiteSpecificHelp(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite', '--help']));

        static::assertSame(0, $exitCode);
        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet run mysuite [options]

            Suite "mysuite" options:
              --sub-filter <value>    Sub-filter tests by name pattern.

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).

            EXPECTED,
            $this->recordingStderr->getOutput()
        );
        static::assertSame('', $this->recordingStdout->getOutput());
    }

    public function testRunWithSuiteNameAsCommandAndHelpOptionDisplaysSuiteSpecificHelp(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'mysuite', '--help']));

        static::assertSame(0, $exitCode);
        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet run mysuite [options]

            Suite "mysuite" options:
              --sub-filter <value>    Sub-filter tests by name pattern.

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).

            EXPECTED,
            $this->recordingStderr->getOutput()
        );
    }

    public function testRunWithHelpOptionAndMissingSuiteWritesErrorAndReturnsOne(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'nonexistent', '--help']));

        static::assertSame(1, $exitCode);
        static::assertSame(
            'Tappet suite config file tappet.nonexistent.suite.php is required but was not found in any of the configured paths: [' . $this->fixturesPath . ']' . PHP_EOL,
            $this->recordingStderr->getOutput()
        );
    }

    public function testRunWithUnrecognisedOptionWritesErrorAndReturnsOne(): void
    {
        $exitCode = $this->tappetBinary->run($this->cliParser->parse(['tappet', 'run', 'mysuite', '--unknown-option', 'value']));

        static::assertSame(1, $exitCode);
        static::assertSame('Error: unrecognised option "--unknown-option".' . PHP_EOL, $this->recordingStderr->getOutput());
    }
}
