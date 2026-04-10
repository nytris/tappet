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
 * Class ParsedArgs.
 *
 * Immutable value object holding the result of parsing CLI arguments.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ParsedArgs implements ParsedArgsInterface
{
    /**
     * @param string $scriptName The script name (argv[0]).
     * @param string|null $command The command name, or null if absent.
     * @param string[] $positionalArgs Positional arguments after the command.
     * @param array<string, string|true> $options Parsed named options.
     */
    public function __construct(
        private readonly string $scriptName,
        private readonly ?string $command,
        private readonly array $positionalArgs,
        private readonly array $options
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getCommand(): ?string
    {
        return $this->command;
    }

    /**
     * @inheritDoc
     */
    public function getOption(string $name): string|bool|null
    {
        return $this->options[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @inheritDoc
     */
    public function getPositionalArg(int $index): ?string
    {
        return $this->positionalArgs[$index] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getScriptName(): string
    {
        return $this->scriptName;
    }
}
