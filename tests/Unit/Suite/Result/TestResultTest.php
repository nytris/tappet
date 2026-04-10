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

namespace Tappet\Tests\Unit\Suite\Result;

use Tappet\Suite\Result\TestResult;
use Tappet\Tests\AbstractTestCase;

/**
 * Class TestResultTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestResultTest extends AbstractTestCase
{
    public function testGetOutputReturnsOutput(): void
    {
        $result = new TestResult('My test output.');

        static::assertSame('My test output.', $result->getOutput());
    }

    public function testHasFailuresReturnsFalseByDefault(): void
    {
        $result = new TestResult('My test output.');

        static::assertFalse($result->hasFailures());
    }

    public function testHasFailuresReturnsFalseWhenSetToFalse(): void
    {
        $result = new TestResult('My test output.', false);

        static::assertFalse($result->hasFailures());
    }

    public function testHasFailuresReturnsTrueWhenSetToTrue(): void
    {
        $result = new TestResult('My test output.', true);

        static::assertTrue($result->hasFailures());
    }
}
