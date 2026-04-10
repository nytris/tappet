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

namespace Tappet\Tests\Unit\Cli\Config;

use Tappet\Cli\Config\Config;
use Tappet\Cli\Implementation\DefaultImplementation;
use Tappet\Cli\Implementation\ImplementationInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ConfigTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ConfigTest extends AbstractTestCase
{
    private Config $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = new Config();
    }

    public function testGetDefaultSuiteReturnsNullInitially(): void
    {
        static::assertNull($this->config->getDefaultSuite());
    }

    public function testIsPresentReturnsTrue(): void
    {
        static::assertTrue($this->config->isPresent());
    }

    public function testSetDefaultSuiteSetsTheSuite(): void
    {
        $this->config->setDefaultSuite('my-suite');

        static::assertSame('my-suite', $this->config->getDefaultSuite());
    }

    public function testSetDefaultSuiteReturnsConfigForFluentInterface(): void
    {
        $result = $this->config->setDefaultSuite('my-suite');

        static::assertSame($this->config, $result);
    }

    public function testGetImplementationReturnsDefaultImplementationWhenNotSet(): void
    {
        static::assertInstanceOf(DefaultImplementation::class, $this->config->getImplementation());
    }

    public function testGetImplementationReturnsSameDefaultInstanceOnSubsequentCalls(): void
    {
        $first = $this->config->getImplementation();
        $second = $this->config->getImplementation();

        static::assertSame($first, $second);
    }

    public function testSetImplementationSetsTheImplementation(): void
    {
        $implementation = mock(ImplementationInterface::class);

        $this->config->setImplementation($implementation);

        static::assertSame($implementation, $this->config->getImplementation());
    }

    public function testSetImplementationReturnsConfigForFluentInterface(): void
    {
        $implementation = mock(ImplementationInterface::class);

        $result = $this->config->setImplementation($implementation);

        static::assertSame($this->config, $result);
    }
}
