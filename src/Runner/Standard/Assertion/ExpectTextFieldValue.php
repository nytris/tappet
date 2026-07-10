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
 * Class ExpectTextFieldValue.
 *
 * Asserts that the given text field has the specified value.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectTextFieldValue implements FieldAssertionInterface
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
     * Fetches the value expected to be set on the text field.
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
