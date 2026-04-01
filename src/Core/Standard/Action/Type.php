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

namespace Tappet\Core\Standard\Action;

use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

/**
 * Class Type.
 *
 * Types text into a field.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Type implements FieldActionInterface
{
    /**
     * @var string
     */
    private $fieldHandle;
    /**
     * @var string
     */
    private $text;

    public function __construct(
        string $fieldHandle,
        string $text
    ) {
        $this->fieldHandle = $fieldHandle;
        $this->text = $text;
    }

    public function getFieldHandle(): string
    {
        return $this->fieldHandle;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performFieldAction($this);
    }
}
