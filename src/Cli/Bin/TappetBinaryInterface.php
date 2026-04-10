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
 * Interface TappetBinaryInterface.
 *
 * Defines the contract for the `tappet` binary entrypoint.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface TappetBinaryInterface
{
    /**
     * Runs the binary with the given parsed CLI arguments.
     *
     * @return int Exit code.
     */
    public function run(ParsedArgsInterface $parsedArgs): int;
}
