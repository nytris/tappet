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
 * Interface ParsedArgsInterface.
 *
 * Represents the result of parsing CLI arguments.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ParsedArgsInterface
{
    /**
     * Returns the command name (the first non-option positional argument), or null if absent.
     */
    public function getCommand(): ?string;

    /**
     * Returns the value of the given option, true if the option was given as a bare flag,
     * or null if the option was not present.
     */
    public function getOption(string $name): string|bool|null;

    /**
     * Returns all parsed options.
     *
     * @return array<string, string|true>
     */
    public function getOptions(): array;

    /**
     * Returns the positional argument at the given zero-based index after the command,
     * or null if not present.
     */
    public function getPositionalArg(int $index): ?string;

    /**
     * Returns the script name (argv[0]).
     */
    public function getScriptName(): string;
}
