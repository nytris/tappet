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
 * Interface CliOptionInterface.
 *
 * Describes a CLI option that a suite supports.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface CliOptionInterface
{
    /**
     * Returns the description of the option, shown in help output.
     */
    public function getDescription(): string;

    /**
     * Returns the name of the option (without the leading '--').
     */
    public function getName(): string;

    /**
     * Returns whether the option is required.
     */
    public function isRequired(): bool;

    /**
     * Returns whether the option expects a value (true) or is a bare boolean flag (false).
     */
    public function isValueExpected(): bool;
}
