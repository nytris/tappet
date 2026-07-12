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

namespace Tappet\Runner\Assertion;

/**
 * Interface FieldAssertionInterface.
 *
 * Represents an assertion performed on a form field.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface FieldAssertionInterface extends AssertionInterface
{
    /**
     * Fetches the unique handle of the field on which the assertion will be performed.
     */
    public function getFieldHandle(): string;
}
