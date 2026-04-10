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
 * Interface InteractionInterface.
 *
 * Represents an action that performs an interaction with the page.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface InteractionInterface extends ActionInterface
{
    /**
     * Fetches the unique handle of the interaction to be performed.
     */
    public function getInteractionHandle(): string;
}
