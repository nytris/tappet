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

namespace Tappet\Suite\Cli;

/**
 * Interface CliSpecInterface.
 *
 * Describes the CLI options that a suite supports.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface CliSpecInterface
{
    /**
     * Returns the CLI options declared by the suite.
     *
     * @return CliOptionInterface[]
     */
    public function getOptions(): array;
}
