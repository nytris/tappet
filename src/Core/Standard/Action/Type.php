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

use Tappet\Core\Action\ActionInterface;
use Tappet\Core\Environment\EnvironmentInterface;

class Type implements ActionInterface
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
        $environment->getField($this->fieldHandle)->type($this->text);
    }
}
