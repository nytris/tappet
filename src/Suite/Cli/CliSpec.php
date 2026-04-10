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
 * Class CliSpec.
 *
 * Immutable value object describing the CLI options that a suite supports.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class CliSpec implements CliSpecInterface
{
    /**
     * @var CliOptionInterface[]
     */
    private $options;

    /**
     * @param CliOptionInterface[] $options
     */
    public function __construct(
        array $options = []
    ) {
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
