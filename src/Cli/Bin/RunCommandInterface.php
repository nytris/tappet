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

use Tappet\Suite\Result\ResultInterface;

/**
 * Interface RunCommandInterface.
 *
 * Defines the contract for the `run` command.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RunCommandInterface
{
    /**
     * Displays CLI help for the given suite (or generic run help if no suite can be resolved)
     * and returns an appropriate exit code.
     *
     * @param string|null $suiteName The name of the suite, or null to use the default from config.
     */
    public function help(?string $suiteName): int;

    /**
     * Runs the `run` command with the given suite name and options.
     *
     * @param string|null $suiteName The name of the suite to run, or null to use the default from config.
     * @param array<string, mixed> $options CLI options.
     */
    public function run(?string $suiteName, array $options): ?ResultInterface;
}
