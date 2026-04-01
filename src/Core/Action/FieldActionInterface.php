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

namespace Tappet\Core\Action;

/**
 * Interface FieldActionInterface.
 *
 * Represents an action that can be performed on a field.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface FieldActionInterface extends ActionInterface
{
    /**
     * Fetches the unique handle of the field on which the action will be performed.
     */
    public function getFieldHandle(): string;
}
