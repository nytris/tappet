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
 * Class Type.
 *
 * Types text into a field.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Type implements FieldActionInterface
{
    public function __construct(
        private readonly string $fieldHandle,
        private readonly string $text
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
     * Fetches the text to type into the field.
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performFieldAction($this);
    }
}
