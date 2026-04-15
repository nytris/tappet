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

use Mockery\MockInterface;
use Tappet\Cli\Bin\RunCommand;
use Tappet\Cli\Config\ConfigInterface;
use Tappet\Cli\Config\MissingConfig;
use Tappet\Cli\Environment\EnvironmentInterface;
use Tappet\Cli\Io\RecordingOutput;
use Tappet\Core\Exception\MissingConfigurationException;
use Tappet\Suite\Cli\CliOption;
use Tappet\Suite\Cli\CliSpec;
use Tappet\Suite\Result\ResultInterface;
use Tappet\Suite\SuiteInterface;
use Tappet\Suite\SuiteResolverInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class RunCommandTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RunCommandTest extends AbstractTestCase
{
    private ConfigInterface&MockInterface $config;
    private EnvironmentInterface&MockInterface $environment;
    private RunCommand $runCommand;
    private RecordingOutput $stderr;
    private RecordingOutput $stdout;
    /** @var SuiteResolverInterface<SuiteInterface>&MockInterface */
    private SuiteResolverInterface&MockInterface $suiteResolver;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = mock(ConfigInterface::class, [
            'getDefaultApiBaseUrl' => null,
            'getDefaultApiKey' => null,
            'getDefaultBaseUrl' => null,
            'getDefaultFilter' => null,
            'isPresent' => true,
        ]);
        $this->environment = mock(EnvironmentInterface::class, [
            'getEnvironmentVariable' => null,
        ]);
        $this->suiteResolver = mock(SuiteResolverInterface::class);
        $this->stdout = new RecordingOutput();
        $this->stderr = new RecordingOutput();

        $this->runCommand = new RunCommand(
            $this->config,
            $this->suiteResolver,
            $this->stdout,
            $this->stderr,
            '/my/project/root',
            $this->environment
        );
    }

    public function testHelpWritesSuiteSpecificHelpAndReturnsZero(): void
    {
        $suite = mock(SuiteInterface::class, [
            'getCliSpec' => new CliSpec([new CliOption('sub-filter', 'Sub-filter tests by name pattern.')]),
        ]);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $exitCode = $this->runCommand->help('my-suite');

        static::assertSame(0, $exitCode);
        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet run my-suite [options]

            Suite "my-suite" options:
              --sub-filter <value>    Sub-filter tests by name pattern.

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.

            EXPECTED,
            $this->stderr->getOutput()
        );
    }

    public function testHelpUsesDefaultSuiteFromConfigWhenNoSuiteNameGiven(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $this->config->allows('getDefaultSuite')->andReturn('default-suite');

        $this->suiteResolver->expects()
            ->resolveSuite('default-suite')
            ->once()
            ->andReturn($suite);

        $exitCode = $this->runCommand->help(null);

        static::assertSame(0, $exitCode);
    }

    public function testHelpWritesGenericHelpWhenNoSuiteNameAndNoDefault(): void
    {
        $this->config->allows('getDefaultSuite')->andReturn(null);

        $exitCode = $this->runCommand->help(null);

        static::assertSame(0, $exitCode);
        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet run [<suite-name>] [options]

            Run 'tappet run <suite-name> --help' for suite-specific options.

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.

            EXPECTED,
            $this->stderr->getOutput()
        );
    }

    public function testHelpReturnsOneAndWritesErrorWhenSuiteFileNotFound(): void
    {
        $exception = new MissingConfigurationException('Suite file not found.');
        $this->suiteResolver->allows('resolveSuite')->andThrow($exception);

        $exitCode = $this->runCommand->help('nonexistent');

        static::assertSame(1, $exitCode);
        static::assertSame('Suite file not found.' . PHP_EOL, $this->stderr->getOutput());
    }

    public function testHelpIndicatesNoAdditionalOptionsWhenSpecIsEmpty(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $this->runCommand->help('my-suite');

        static::assertSame(
            <<<'EXPECTED'
            Usage: tappet run my-suite [options]

            Suite "my-suite" declares no additional options.

            Global options:
              --api-base-url <url>    Base URL of the Tappet API (or TAPPET_API_BASE_URL env var).
              --api-key <key>         Tappet API key (or TAPPET_API_KEY env var).
              --base-url <url>        Base URL of the GUI application under test (or TAPPET_BASE_URL env var).
              --filter <pattern>      Filter tests by name pattern.

            EXPECTED,
            $this->stderr->getOutput()
        );
    }

    public function testHelpReturnsExitCodeOneAndWritesErrorWhenConfigIsNotPresent(): void
    {
        $runCommand = new RunCommand(
            new MissingConfig(),
            $this->suiteResolver,
            $this->stdout,
            $this->stderr,
            '/my/project/root',
            $this->environment
        );

        $exitCode = $runCommand->help('my-suite');

        static::assertSame(1, $exitCode);
        static::assertSame(
            'Error: Tappet config file (tappet.config.php) not found. Ensure it exists in your project root, or use --project to specify a path.' . PHP_EOL,
            $this->stderr->getOutput()
        );
    }

    public function testRunReturnsNullAndWritesErrorWhenConfigIsNotPresent(): void
    {
        $runCommand = new RunCommand(
            new MissingConfig(),
            $this->suiteResolver,
            $this->stdout,
            $this->stderr,
            '/my/project/root',
            $this->environment
        );

        $result = $runCommand->run('my-suite', []);

        static::assertNull($result);
        static::assertSame(
            'Error: Tappet config file (tappet.config.php) not found. Ensure it exists in your project root, or use --project to specify a path.' . PHP_EOL,
            $this->stderr->getOutput()
        );
    }

    public function testRunUsesGivenSuiteNameWhenNotNull(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);

        $this->suiteResolver->expects()
            ->resolveSuite('my-suite')
            ->once()
            ->andReturn($suite);
        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'test-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $runResult = $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'test-key',
        ]);

        static::assertSame($result, $runResult);
    }

    public function testRunUsesDefaultSuiteFromConfigWhenSuiteNameIsNull(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->config->allows('getDefaultSuite')->andReturn('default-suite');

        $this->suiteResolver->expects()
            ->resolveSuite('default-suite')
            ->once()
            ->andReturn($suite);
        $suite->expects()
            ->run(
                '/my/project/root',
                'default-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'test-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $runResult = $this->runCommand->run(null, [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'test-key',
        ]);

        static::assertSame($result, $runResult);
    }

    public function testRunReturnsNullAndWritesErrorWhenNoSuiteNameAndNoDefault(): void
    {
        $this->config->allows('getDefaultSuite')->andReturn(null);

        $result = $this->runCommand->run(null, []);

        static::assertNull($result);
        static::assertSame('Error: no suite name specified and no default suite configured.' . PHP_EOL, $this->stderr->getOutput());
    }

    public function testRunReturnsNullAndWritesErrorOnMissingConfigurationException(): void
    {
        $exception = new MissingConfigurationException('Suite file not found.');
        $this->suiteResolver->allows('resolveSuite')->andThrow($exception);

        $result = $this->runCommand->run('my-suite', []);

        static::assertNull($result);
        static::assertSame('Suite file not found.' . PHP_EOL, $this->stderr->getOutput());
    }

    public function testRunPrintsResultOutputToStdout(): void
    {
        $result = mock(ResultInterface::class, ['getOutput' => 'My suite output']);
        $suite = mock(SuiteInterface::class, [
            'getCliSpec' => new CliSpec([]),
            'run' => $result,
        ]);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'test-key',
        ]);

        static::assertSame('My suite output', $this->stdout->getOutput());
    }

    public function testRunPassesOptionsToSuite(): void
    {
        $suite = mock(SuiteInterface::class, [
            'getCliSpec' => new CliSpec([
                new CliOption('verbose', 'Enable verbose output.', false)
            ])
        ]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'test-key',
                null,
                ['verbose' => true]
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['base-url' => 'https://gui.example.com', 'api-base-url' => 'https://api.example.com', 'api-key' => 'test-key', 'verbose' => true]);
    }

    public function testRunReturnsNullAndWritesErrorWhenNoBaseUrl(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $result = $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com', 'api-key' => 'test-key']);

        static::assertNull($result);
        static::assertSame(
            'Error: no base URL specified. Provide one via --base-url or the TAPPET_BASE_URL environment variable.' . PHP_EOL,
            $this->stderr->getOutput()
        );
    }

    public function testRunReturnsNullAndWritesErrorWhenNoApiBaseUrl(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $result = $this->runCommand->run('my-suite', ['base-url' => 'https://gui.example.com', 'api-key' => 'test-key']);

        static::assertNull($result);
        static::assertSame(
            'Error: no API base URL specified. Provide one via --api-base-url or the TAPPET_API_BASE_URL environment variable.' . PHP_EOL,
            $this->stderr->getOutput()
        );
    }

    public function testRunReturnsNullAndWritesErrorWhenNoApiKey(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $result = $this->runCommand->run('my-suite', ['base-url' => 'https://gui.example.com', 'api-base-url' => 'https://api.example.com']);

        static::assertNull($result);
        static::assertSame(
            'Error: no API key specified. Provide one via --api-key or the TAPPET_API_KEY environment variable.' . PHP_EOL,
            $this->stderr->getOutput()
        );
    }

    public function testRunReturnsNullAndWritesErrorForUnrecognisedOption(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $result = $this->runCommand->run('my-suite', ['unknown-option' => 'value']);

        static::assertNull($result);
        static::assertSame('Error: unrecognised option "--unknown-option".' . PHP_EOL, $this->stderr->getOutput());
    }

    public function testRunAllowsOptionsDeclaredBySuiteSpec(): void
    {
        $suite = mock(SuiteInterface::class, [
            'getCliSpec' => new CliSpec([
                new CliOption('sub-filter', 'Sub-filter tests by name pattern.')
            ])
        ]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'test-key',
                null,
                ['sub-filter' => 'my-test']
            )
            ->once()
            ->andReturn($result);

        $runResult = $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'test-key',
            'sub-filter' => 'my-test',
        ]);

        static::assertSame($result, $runResult);
    }

    public function testRunResolvesApiBaseUrlFromOption(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://option.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://option.example.com',
            'api-key' => 'my-api-key',
        ]);
    }

    public function testRunResolvesApiBaseUrlFromEnvironmentVariable(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_API_BASE_URL')
            ->andReturn('https://env.example.com');
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_BASE_URL')
            ->andReturn('https://gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://env.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-key' => 'my-api-key']);
    }

    public function testRunPrefersApiBaseUrlOptionOverEnvironmentVariable(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_API_BASE_URL')
            ->andReturn('https://env.example.com');
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_BASE_URL')
            ->andReturn('https://gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://option.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://option.example.com', 'api-key' => 'my-api-key']);
    }

    public function testRunResolvesApiKeyFromOption(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'my-api-key',
        ]);
    }

    public function testRunResolvesApiKeyFromEnvironmentVariable(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_API_KEY')
            ->andReturn('env-api-key');
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_BASE_URL')
            ->andReturn('https://gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'env-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com']);
    }

    public function testRunPrefersApiKeyOptionOverEnvironmentVariable(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_API_KEY')
            ->andReturn('env-api-key');
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_BASE_URL')
            ->andReturn('https://gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'option-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com', 'api-key' => 'option-api-key']);
    }

    public function testRunResolvesBaseUrlFromOption(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://option-gui.example.com',
                'https://api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://option-gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'my-api-key',
        ]);
    }

    public function testRunResolvesBaseUrlFromEnvironmentVariable(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_BASE_URL')
            ->andReturn('https://env-gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://env-gui.example.com',
                'https://api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com', 'api-key' => 'my-api-key']);
    }

    public function testRunPrefersBaseUrlOptionOverEnvironmentVariable(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_BASE_URL')
            ->andReturn('https://env-gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://option-gui.example.com',
                'https://api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://option-gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'my-api-key',
        ]);
    }

    public function testRunPassesFilterToSuite(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'my-api-key',
                'login',
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'my-api-key',
            'filter' => 'login',
        ]);
    }

    public function testRunPassesNullFilterToSuiteWhenNoFilterOption(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'my-api-key',
        ]);
    }

    public function testRunResolvesBaseUrlFromConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultBaseUrl')->andReturn('https://config-gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://config-gui.example.com',
                'https://api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com', 'api-key' => 'my-api-key']);
    }

    public function testRunPrefersBaseUrlEnvVarOverConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultBaseUrl')->andReturn('https://config-gui.example.com');
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_BASE_URL')
            ->andReturn('https://env-gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://env-gui.example.com',
                'https://api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com', 'api-key' => 'my-api-key']);
    }

    public function testRunResolvesApiBaseUrlFromConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultApiBaseUrl')->andReturn('https://config-api.example.com');
        $this->config->allows('getDefaultBaseUrl')->andReturn('https://gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://config-api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-key' => 'my-api-key']);
    }

    public function testRunPrefersApiBaseUrlEnvVarOverConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultApiBaseUrl')->andReturn('https://config-api.example.com');
        $this->config->allows('getDefaultBaseUrl')->andReturn('https://gui.example.com');
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_API_BASE_URL')
            ->andReturn('https://env-api.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://env-api.example.com',
                'my-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-key' => 'my-api-key']);
    }

    public function testRunResolvesApiKeyFromConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultApiKey')->andReturn('config-api-key');
        $this->config->allows('getDefaultBaseUrl')->andReturn('https://gui.example.com');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'config-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com']);
    }

    public function testRunPrefersApiKeyEnvVarOverConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultApiKey')->andReturn('config-api-key');
        $this->config->allows('getDefaultBaseUrl')->andReturn('https://gui.example.com');
        $this->environment->allows('getEnvironmentVariable')
            ->with('TAPPET_API_KEY')
            ->andReturn('env-api-key');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'env-api-key',
                null,
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', ['api-base-url' => 'https://api.example.com']);
    }

    public function testRunResolvesFilterFromConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultFilter')->andReturn('config-filter');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'my-api-key',
                'config-filter',
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'my-api-key',
        ]);
    }

    public function testRunPrefersFilterOptionOverConfigDefault(): void
    {
        $suite = mock(SuiteInterface::class, ['getCliSpec' => new CliSpec([])]);
        $result = mock(ResultInterface::class, ['getOutput' => '']);
        $this->suiteResolver->allows('resolveSuite')->andReturn($suite);
        $this->config->allows('getDefaultFilter')->andReturn('config-filter');

        $suite->expects()
            ->run(
                '/my/project/root',
                'my-suite',
                'https://gui.example.com',
                'https://api.example.com',
                'my-api-key',
                'option-filter',
                []
            )
            ->once()
            ->andReturn($result);

        $this->runCommand->run('my-suite', [
            'base-url' => 'https://gui.example.com',
            'api-base-url' => 'https://api.example.com',
            'api-key' => 'my-api-key',
            'filter' => 'option-filter',
        ]);
    }
}
