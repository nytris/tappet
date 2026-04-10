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

namespace Tappet\Suite\Cli;

/**
 * Class CliOption.
 *
 * Immutable value object describing a CLI option that a suite supports.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class CliOption implements CliOptionInterface
{
    /**
     * @var string
     */
    private $description;
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $required;

    /**
     * @var bool
     */
    private $valueExpected;

    public function __construct(
        string $name,
        string $description,
        bool $valueExpected = true,
        bool $required = false
    ) {
        $this->description = $description;
        $this->name = $name;
        $this->required = $required;
        $this->valueExpected = $valueExpected;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @inheritDoc
     */
    public function isValueExpected(): bool
    {
        return $this->valueExpected;
    }
}
