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

namespace Tappet\Suite\Result;

/**
 * Interface ResultInterface.
 *
 * Represents the result of a test run.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ResultInterface
{
    /**
     * Fetches the output of the test run.
     */
    public function getOutput(): string;

    /**
     * Determines whether the test run had any failures.
     */
    public function hasFailures(): bool;
}
