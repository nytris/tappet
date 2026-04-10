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
 * Class CliParser.
 *
 * Parses CLI arguments, supporting `--option value`, `--option=value` and `--option` formats.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class CliParser implements CliParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(array $argv): ParsedArgsInterface
    {
        $scriptName = (string) array_shift($argv);
        $command = null;
        $options = [];
        $positionalArgs = [];

        for ($i = 0, $count = count($argv); $i < $count; $i++) {
            $arg = $argv[$i];

            if (str_starts_with($arg, '--')) {
                if (str_contains($arg, '=')) {
                    [$name, $value] = explode('=', substr($arg, 2), 2);
                    $options[$name] = $value;
                } else {
                    $name = substr($arg, 2);
                    $nextArg = $argv[$i + 1] ?? null;

                    if ($nextArg !== null && !str_starts_with($nextArg, '--')) {
                        $options[$name] = $nextArg;
                        $i++;
                    } else {
                        $options[$name] = true;
                    }
                }
            } elseif ($command === null) {
                $command = $arg;
            } else {
                $positionalArgs[] = $arg;
            }
        }

        return new ParsedArgs($scriptName, $command, $positionalArgs, $options);
    }
}
