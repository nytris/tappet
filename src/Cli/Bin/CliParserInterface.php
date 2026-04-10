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

/**
 * Interface CliParserInterface.
 *
 * Defines the contract for parsing CLI arguments.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface CliParserInterface
{
    /**
     * Parses the given argv array into a structured result.
     *
     * Options support `--option value`, `--option=value` and `--flag` formats.
     * The first non-option argument after the script name is treated as the command.
     * Subsequent non-option arguments are treated as positional arguments.
     *
     * @param string[] $argv
     */
    public function parse(array $argv): ParsedArgsInterface;
}
