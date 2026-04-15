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

namespace Tappet\Tests\Unit\Cli\Bin;

use Composer\InstalledVersions;
use Mockery\MockInterface;
use Tappet\Cli\Bin\CliParser;
use Tappet\Cli\Bin\RunCommandInterface;
use Tappet\Cli\Bin\TappetBinary;
use Tappet\Cli\Io\RecordingOutput;
use Tappet\Suite\Result\ResultInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class TappetBinaryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TappetBinaryTest extends AbstractTestCase
{
    private TappetBinary $binary;
    private CliParser $cliParser;
    private RunCommandInterface&MockInterface $runCommand;
    private RecordingOutput $stderr;

    public function setUp(): void
    {
        parent::setUp();

        $this->cliParser = new CliParser();
        $this->runCommand = mock(RunCommandInterface::class);
        $this->stderr = new RecordingOutput();

        $this->binary = new TappetBinary($this->runCommand, $this->stderr);
    }

    public function testRunWithNoArgsDefaultsToRunCommandWithNullSuiteName(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => false,
        ]);

        $this->runCommand->expects()
            ->run(null, [])
            ->once()
            ->andReturn($result);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet']));

        static::assertSame(0, $exitCode);
    }

    public function testRunWithRunCommandAndNoSuiteNamePassesNullSuiteName(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => false,
        ]);

        $this->runCommand->expects()
            ->run(null, [])
            ->once()
            ->andReturn($result);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'run']));

        static::assertSame(0, $exitCode);
    }

    public function testRunWithRunCommandAndSuiteNamePassesSuiteName(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => false,
        ]);

        $this->runCommand->expects()
            ->run('my-suite', [])
            ->once()
            ->andReturn($result);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'run', 'my-suite']));

        static::assertSame(0, $exitCode);
    }

    public function testRunWithRunCommandAndSuiteNameWithOptionsPassesSuiteNameAndOneOption(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => false,
        ]);

        $this->runCommand->expects()
            ->run('my-suite', ['my-option' => 'yes'])
            ->once()
            ->andReturn($result);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'run', 'my-suite', '--my-option', 'yes']));

        static::assertSame(0, $exitCode);
    }

    public function testRunWithRunCommandAndSuiteNameWithOptionsPassesSuiteNameAndMultipleOptions(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => false,
        ]);

        $this->runCommand->expects()
            ->run('my-suite', ['my-option' => 'one', 'another-option' => 'two'])
            ->once()
            ->andReturn($result);

        $exitCode = $this->binary->run(
            $this->cliParser->parse(
                ['tappet', 'run', 'my-suite', '--my-option', 'one', '--another-option', 'two']
            )
        );

        static::assertSame(0, $exitCode);
    }

    public function testRunWithRunCommandAndSuiteNameWithEqualsStyleOptionParsesCorrectly(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => false,
        ]);

        $this->runCommand->expects()
            ->run('my-suite', ['my-option' => 'yes'])
            ->once()
            ->andReturn($result);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'run', 'my-suite', '--my-option=yes']));

        static::assertSame(0, $exitCode);
    }

    public function testRunReturnsExitCodeZeroWhenNoFailures(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => false,
        ]);
        $this->runCommand->allows('run')->andReturn($result);

        static::assertSame(0, $this->binary->run($this->cliParser->parse(['tappet'])));
    }

    public function testRunReturnsExitCodeThreeWhenHasFailures(): void
    {
        $result = mock(ResultInterface::class, [
            'hasFailures' => true,
        ]);
        $this->runCommand->allows('run')->andReturn($result);

        static::assertSame(3, $this->binary->run($this->cliParser->parse(['tappet'])));
    }

    public function testRunReturnsExitCodeOneWhenRunCommandReturnsNull(): void
    {
        $this->runCommand->allows('run')->andReturn(null);

        static::assertSame(1, $this->binary->run($this->cliParser->parse(['tappet'])));
    }

    public function testRunPrintsVersionAndReturnsExitCodeZero(): void
    {
        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'version']));

        $version = InstalledVersions::getPrettyVersion('tappet/tappet') ?? '(version unknown)';
        static::assertSame('Nytris Tappet ' . $version . PHP_EOL, $this->stderr->getOutput());
        static::assertSame(0, $exitCode);
    }

    public function testRunPrintsHelpAndReturnsExitCodeZero(): void
    {
        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'help']));

        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet [<command>] [options]

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.
              --project <path>        Path to the project root directory.

            Commands:
              run [<suite-name>]      Run the given suite, or the default one if none is specified.
              help                    Show CLI help and exit.
              version                 Print the Tappet version.

            Run 'tappet run <suite-name> --help' for suite-specific help.

            EXPECTED,
            $this->stderr->getOutput()
        );
        static::assertSame(0, $exitCode);
    }

    public function testRunUsageIncludesApiBaseUrlAndApiKey(): void
    {
        $this->binary->run($this->cliParser->parse(['tappet', 'help']));

        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet [<command>] [options]

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.
              --project <path>        Path to the project root directory.

            Commands:
              run [<suite-name>]      Run the given suite, or the default one if none is specified.
              help                    Show CLI help and exit.
              version                 Print the Tappet version.

            Run 'tappet run <suite-name> --help' for suite-specific help.

            EXPECTED,
            $this->stderr->getOutput()
        );
    }

    public function testRunUsageIncludesSuiteHelpHint(): void
    {
        $this->binary->run($this->cliParser->parse(['tappet', 'help']));

        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet [<command>] [options]

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.
              --project <path>        Path to the project root directory.

            Commands:
              run [<suite-name>]      Run the given suite, or the default one if none is specified.
              help                    Show CLI help and exit.
              version                 Print the Tappet version.

            Run 'tappet run <suite-name> --help' for suite-specific help.

            EXPECTED,
            $this->stderr->getOutput()
        );
    }

    public function testRunWithHelpFlagAndRunCommandDelegatesToRunCommandHelp(): void
    {
        $this->runCommand->expects()
            ->help('my-suite')
            ->once()
            ->andReturn(0);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'run', 'my-suite', '--help']));

        static::assertSame(0, $exitCode);
    }

    public function testRunWithHelpFlagAndNoSuiteNameDelegatesToRunCommandHelp(): void
    {
        $this->runCommand->expects()
            ->help(null)
            ->once()
            ->andReturn(0);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'run', '--help']));

        static::assertSame(0, $exitCode);
    }

    public function testRunWithSuiteNameAsCommandAndHelpFlagDelegatesToRunCommandHelp(): void
    {
        $this->runCommand->expects()
            ->help('my-suite')
            ->once()
            ->andReturn(0);

        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'my-suite', '--help']));

        static::assertSame(0, $exitCode);
    }

    public function testRunWithGlobalHelpFlagAndNoCommandPrintsUsageAndReturnsExitCodeZero(): void
    {
        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', '--help']));

        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet [<command>] [options]

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.
              --project <path>        Path to the project root directory.

            Commands:
              run [<suite-name>]      Run the given suite, or the default one if none is specified.
              help                    Show CLI help and exit.
              version                 Print the Tappet version.

            Run 'tappet run <suite-name> --help' for suite-specific help.

            EXPECTED,
            $this->stderr->getOutput()
        );
        static::assertSame(0, $exitCode);
    }

    public function testRunPrintsErrorAndUsageForUnknownCommandAndReturnsExitCodeOne(): void
    {
        $exitCode = $this->binary->run($this->cliParser->parse(['tappet', 'unknown-cmd']));

        static::assertSame(
            <<<'EXPECTED'
            Error: Unknown command "unknown-cmd"

            Usage: tappet [<command>] [options]

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.
              --project <path>        Path to the project root directory.

            Commands:
              run [<suite-name>]      Run the given suite, or the default one if none is specified.
              help                    Show CLI help and exit.
              version                 Print the Tappet version.

            Run 'tappet run <suite-name> --help' for suite-specific help.

            EXPECTED,
            $this->stderr->getOutput()
        );
        static::assertSame(1, $exitCode);
    }
}
