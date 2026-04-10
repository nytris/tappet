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

namespace Tappet\Tests\Unit\Cli\Environment;

use Tappet\Cli\Environment\Environment;
use Tappet\Tests\AbstractTestCase;

/**
 * Class EnvironmentTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EnvironmentTest extends AbstractTestCase
{
    private Environment $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = new Environment();
    }

    public function testGetEnvironmentVariableReturnsValueWhenSet(): void
    {
        putenv('TAPPET_TEST_VAR=my-value');

        try {
            static::assertSame('my-value', $this->environment->getEnvironmentVariable('TAPPET_TEST_VAR'));
        } finally {
            putenv('TAPPET_TEST_VAR');
        }
    }

    public function testGetEnvironmentVariableReturnsNullWhenNotSet(): void
    {
        putenv('TAPPET_TEST_VAR');

        static::assertNull($this->environment->getEnvironmentVariable('TAPPET_TEST_VAR'));
    }
}
