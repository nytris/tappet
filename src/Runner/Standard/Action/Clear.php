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

namespace Tappet\Runner\Standard\Action;

use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class Clear.
 *
 * Clears the value of a text field without typing anything new.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Clear implements FieldActionInterface
{
    public function __construct(
        private readonly string $fieldHandle
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getFieldHandle(): string
    {
        return $this->fieldHandle;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performFieldAction($this);
    }
}
