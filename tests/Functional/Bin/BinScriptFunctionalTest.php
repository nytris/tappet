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

namespace Tappet\Tests\Functional\Bin;

use Tappet\Tests\Functional\AbstractFunctionalTestCase;

/**
 * Class BinScriptFunctionalTest.
 *
 * Functional tests for the bin/tappet script, exercised as a subprocess.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class BinScriptFunctionalTest extends AbstractFunctionalTestCase
{
    private string $binPath;
    private string $fixturesPath;

    public function setUp(): void
    {
        parent::setUp();

        $this->binPath = __DIR__ . '/../../../bin/tappet';
        $this->fixturesPath = __DIR__ . '/../Fixtures/TappetConfiguredApp';
    }

    /**
     * Runs bin/tappet as a subprocess with the given arguments and environment variables.
     *
     * @param list<string> $args
     * @param array<string, string> $env
     * @return array{exitCode: int, stdout: string, stderr: string}
     */
    private function runBin(array $args, array $env = []): array
    {
        $command = PHP_BINARY . ' ' . escapeshellarg($this->binPath);

        foreach ($args as $arg) {
            $command .= ' ' . escapeshellarg($arg);
        }

        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptors, $pipes, null, $env);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        return ['exitCode' => $exitCode, 'stdout' => $stdout, 'stderr' => $stderr];
    }

    public function testProjectOptionWithSpaceResolvesConfigFromGivenDirectory(): void
    {
        $result = $this->runBin(
            ['--project', $this->fixturesPath, 'run', 'mysuite'],
            [
                'TAPPET_BASE_URL' => 'https://gui.example.com',
                'TAPPET_API_BASE_URL' => 'https://project-option.example.com',
                'TAPPET_API_KEY' => 'project-option-key',
            ]
        );

        static::assertSame(0, $result['exitCode']);
        static::assertSame(
            'Test suite "mysuite" output. Base URL: "https://gui.example.com". API base URL: "https://project-option.example.com". API key: "project-option-key".',
            $result['stdout']
        );
        static::assertSame('', $result['stderr']);
    }

    public function testProjectOptionWithEqualsSignResolvesConfigFromGivenDirectory(): void
    {
        $result = $this->runBin(
            ['--project=' . $this->fixturesPath, 'run', 'mysuite'],
            [
                'TAPPET_BASE_URL' => 'https://gui.example.com',
                'TAPPET_API_BASE_URL' => 'https://project-option.example.com',
                'TAPPET_API_KEY' => 'project-option-key',
            ]
        );

        static::assertSame(0, $result['exitCode']);
        static::assertSame(
            'Test suite "mysuite" output. Base URL: "https://gui.example.com". API base URL: "https://project-option.example.com". API key: "project-option-key".',
            $result['stdout']
        );
        static::assertSame('', $result['stderr']);
    }

    public function testProjectOptionWithCommandOmittedAndNoOtherOptionsSucceeds(): void
    {
        $result = $this->runBin(
            ['--project', $this->fixturesPath],
            [
                'TAPPET_BASE_URL' => 'https://gui.example.com',
                'TAPPET_API_BASE_URL' => 'https://project-option.example.com',
                'TAPPET_API_KEY' => 'project-option-key',
            ]
        );

        static::assertSame(0, $result['exitCode']);
        static::assertSame(
            'Test suite "mysuite" output. Base URL: "https://gui.example.com". API base URL: "https://project-option.example.com". API key: "project-option-key".',
            $result['stdout']
        );
        static::assertSame('', $result['stderr']);
    }

    public function testProjectOptionWithInvalidDirectoryWritesErrorToStderrAndReturnsExitCodeOne(): void
    {
        $result = $this->runBin(
            ['--project', '/nonexistent/path', 'run', 'mysuite'],
            [
                'TAPPET_API_BASE_URL' => 'https://project-option.example.com',
                'TAPPET_API_KEY' => 'project-option-key',
            ]
        );

        static::assertSame(1, $result['exitCode']);
        static::assertStringContainsString('tappet.config.php', $result['stderr']);
        static::assertStringContainsString('not found', $result['stderr']);
    }

    public function testApiBaseUrlAndApiKeyOptionsWithoutSuiteNameDefaultToDefaultSuite(): void
    {
        $result = $this->runBin(
            [
                '--project',
                $this->fixturesPath,
                '--base-url',
                'https://gui.example.com',
                '--api-base-url',
                'https://option.example.com',
                '--api-key',
                'option-key',
            ]
        );

        static::assertSame(0, $result['exitCode']);
        static::assertSame(
            'Test suite "mysuite" output. Base URL: "https://gui.example.com". API base URL: "https://option.example.com". API key: "option-key".',
            $result['stdout']
        );
        static::assertSame('', $result['stderr']);
    }

    public function testHelpCommandDisplaysUsageWithoutConfig(): void
    {
        // Runs without --project, so no tappet.config.php is found, but `help` must still work.
        $result = $this->runBin(['help']);

        static::assertSame(0, $result['exitCode']);
        static::assertStringContainsString('Usage: tappet', $result['stderr']);
        static::assertSame('', $result['stdout']);
    }

    public function testGlobalHelpFlagDisplaysUsageWithoutConfig(): void
    {
        // Runs without --project, so no tappet.config.php is found, but --help must still work.
        $result = $this->runBin(['--help']);

        static::assertSame(0, $result['exitCode']);
        static::assertStringContainsString('Usage: tappet', $result['stderr']);
        static::assertSame('', $result['stdout']);
    }

    public function testVersionCommandDisplaysVersionWithoutConfig(): void
    {
        // Runs without --project, so no tappet.config.php is found, but `version` must still work.
        $result = $this->runBin(['version']);

        static::assertSame(0, $result['exitCode']);
        static::assertStringContainsString('Nytris Tappet', $result['stderr']);
        static::assertSame('', $result['stdout']);
    }

    public function testWithoutProjectOptionUsesProjectRootResolver(): void
    {
        // Without --project, the script uses ProjectRootResolver (Composer ClassLoader path).
        // As this test runs inside the tappet project itself, it will find the project root here,
        // which has no tappet.config.php, so it should fail with a missing config error.
        $result = $this->runBin(
            ['run', 'mysuite'],
            [
                'TAPPET_API_BASE_URL' => 'https://project-option.example.com',
                'TAPPET_API_KEY' => 'project-option-key',
            ]
        );

        static::assertSame(1, $result['exitCode']);
        static::assertStringContainsString('tappet.config.php', $result['stderr']);
        static::assertStringContainsString('not found', $result['stderr']);
    }
}
