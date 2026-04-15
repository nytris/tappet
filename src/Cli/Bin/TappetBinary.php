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

use Composer\InstalledVersions;
use Tappet\Cli\Io\OutputInterface;

/**
 * Class TappetBinary.
 *
 * `tappet` binary entrypoint.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TappetBinary implements TappetBinaryInterface
{
    private const KNOWN_COMMANDS = ['help', 'run', 'version'];

    public function __construct(
        private readonly RunCommandInterface $runCommand,
        private readonly OutputInterface $stderr
    ) {
    }

    /**
     * Runs the binary with the given parsed CLI arguments.
     *
     * @return int Exit code.
     */
    public function run(ParsedArgsInterface $parsedArgs): int
    {
        $command = $parsedArgs->getCommand();
        $hasHelpOption = $parsedArgs->getOption('help') === true;

        // Allow `tappet --help` as an alias for `tappet help`.
        if ($command === null && $hasHelpOption) {
            $this->printUsage();

            return 0;
        }

        $command ??= 'run';

        // Allow `tappet <suite-name> --help` as a shorthand for suite-specific help.
        if ($hasHelpOption && !in_array($command, self::KNOWN_COMMANDS, true)) {
            return $this->runCommand->help($command);
        }

        switch ($command) {
            case 'help':
                $this->printUsage();

                return 0;
            case 'run':
                $suiteName = $parsedArgs->getPositionalArg(0);

                if ($hasHelpOption) {
                    return $this->runCommand->help($suiteName);
                }

                $result = $this->runCommand->run(
                    $suiteName,
                    $parsedArgs->getOptions()
                );

                break;
            case 'version':
                $version = InstalledVersions::getPrettyVersion('tappet/tappet') ?? '(version unknown)';

                $this->stderr->write('Nytris Tappet ' . $version . PHP_EOL);

                return 0;
            default:
                $this->stderr->write('Error: Unknown command "' . $command . '"' . PHP_EOL . PHP_EOL);

                return $this->printUsage();
        }

        if ($result === null) {
            return 1; // Configuration error or similar.
        }

        // Exit with non-zero code if there were test failures.
        return $result->hasFailures() ? 3 : 0;
    }

    private function printUsage(): int
    {
        $this->stderr->write(<<<USAGE
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

USAGE);

        return 1;
    }
}
