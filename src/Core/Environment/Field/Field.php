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

namespace Tappet\Core\Environment\Field;

use Tappet\Core\Automation\AutomationInterface;

class Field implements FieldInterface
{
    /**
     * @var AutomationInterface
     */
    private $automation;
    /**
     * @var string
     */
    private $handle;

    public function __construct(AutomationInterface $automation, string $handle)
    {
        $this->automation = $automation;
        $this->handle = $handle;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function type(string $text): void
    {
        $this->automation->typeField($this->handle, $text);
    }
}
