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

namespace Tappet\Core\Environment\Interaction;

/**
 * Interface InteractionInterface.
 *
 * Represents an interaction with the page, such as clicking a specific button.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface InteractionInterface
{
    public function getHandle(): string;

    public function perform(): void;
}
