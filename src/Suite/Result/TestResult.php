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

namespace Tappet\Suite\Result;

/**
 * Class TestResult.
 *
 * Represents the result of a test run.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestResult implements ResultInterface
{
    /**
     * @var bool
     */
    private $hasFailures;
    /**
     * @var string
     */
    private $output;

    public function __construct(
        string $output,
        bool $hasFailures = false
    ) {
        $this->hasFailures = $hasFailures;
        $this->output = $output;
    }

    /**
     * @inheritDoc
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @inheritDoc
     */
    public function hasFailures(): bool
    {
        return $this->hasFailures;
    }
}
