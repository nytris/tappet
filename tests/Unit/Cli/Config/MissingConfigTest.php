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

use Tappet\Cli\Config\MissingConfig;
use Tappet\Cli\Implementation\DefaultImplementation;
use Tappet\Cli\Implementation\ImplementationInterface;
use Tappet\Core\Exception\MissingConfigurationException;
use Tappet\Tests\AbstractTestCase;

/**
 * Class MissingConfigTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MissingConfigTest extends AbstractTestCase
{
    private MissingConfig $config;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = new MissingConfig();
    }

    public function testGetDefaultApiBaseUrlReturnsNull(): void
    {
        static::assertNull($this->config->getDefaultApiBaseUrl());
    }

    public function testGetDefaultApiKeyReturnsNull(): void
    {
        static::assertNull($this->config->getDefaultApiKey());
    }

    public function testGetDefaultBaseUrlReturnsNull(): void
    {
        static::assertNull($this->config->getDefaultBaseUrl());
    }

    public function testGetDefaultFilterReturnsNull(): void
    {
        static::assertNull($this->config->getDefaultFilter());
    }

    public function testGetDefaultSuiteReturnsNull(): void
    {
        static::assertNull($this->config->getDefaultSuite());
    }

    public function testGetImplementationReturnsDefaultImplementation(): void
    {
        static::assertInstanceOf(DefaultImplementation::class, $this->config->getImplementation());
    }

    public function testGetImplementationReturnsSameInstanceOnSubsequentCalls(): void
    {
        $first = $this->config->getImplementation();
        $second = $this->config->getImplementation();

        static::assertSame($first, $second);
    }

    public function testIsPresentReturnsFalse(): void
    {
        static::assertFalse($this->config->isPresent());
    }

    public function testSetDefaultApiBaseUrlRaisesException(): void
    {
        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage('Cannot set default API base URL for a MissingConfig - did you mean to use Config?');

        $this->config->setDefaultApiBaseUrl('https://api.example.com');
    }

    public function testSetDefaultApiKeyRaisesException(): void
    {
        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage('Cannot set default API key for a MissingConfig - did you mean to use Config?');

        $this->config->setDefaultApiKey('my-key');
    }

    public function testSetDefaultBaseUrlRaisesException(): void
    {
        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage('Cannot set default base URL for a MissingConfig - did you mean to use Config?');

        $this->config->setDefaultBaseUrl('https://myapp.example.com');
    }

    public function testSetDefaultFilterRaisesException(): void
    {
        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage('Cannot set default filter for a MissingConfig - did you mean to use Config?');

        $this->config->setDefaultFilter('login');
    }

    public function testSetDefaultSuiteRaisesException(): void
    {
        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage('Cannot set default suite for a MissingConfig - did you mean to use Config?');

        $this->config->setDefaultSuite('my-suite');
    }

    public function testSetImplementationRaisesException(): void
    {
        $implementation = mock(ImplementationInterface::class);

        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage('Cannot set implementation for a MissingConfig - did you mean to use Config?');

        $this->config->setImplementation($implementation);
    }
}
