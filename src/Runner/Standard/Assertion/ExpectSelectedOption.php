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

namespace Tappet\Runner\Standard\Assertion;

use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class ExpectSelectedOption.
 *
 * Asserts that the given select field has the specified option selected.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectSelectedOption implements FieldAssertionInterface
{
    public function __construct(
        private readonly string $fieldHandle,
        private readonly string $value,
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
     * Fetches the value of the option expected to be selected for the field.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performFieldAssertion($this);
    }
}
