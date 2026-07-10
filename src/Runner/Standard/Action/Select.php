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
 * Class Select.
 *
 * Selects an option of a select dropdown field.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Select implements FieldActionInterface
{
    public function __construct(
        private readonly string $fieldHandle,
        private readonly string $optionValue
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
     * Fetches the value of the option to select.
     */
    public function getOptionValue(): string
    {
        return $this->optionValue;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performFieldAction($this);
    }
}
