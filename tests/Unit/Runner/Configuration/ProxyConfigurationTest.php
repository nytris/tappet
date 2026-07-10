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

namespace Tappet\Tests\Unit\Runner\Configuration;

use Tappet\Runner\Configuration\ProxyConfiguration;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ProxyConfigurationTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ProxyConfigurationTest extends AbstractTestCase
{
    private ProxyConfiguration $proxyConfiguration;
    private string $capturedApiBaseUrl = '';
    private string $capturedBaseUrl = '';

    public function setUp(): void
    {
        parent::setUp();

        $this->proxyConfiguration = new ProxyConfiguration(
            getApiBaseUrlCallback: fn () => 'https://api.example.com',
            getBaseUrlCallback: fn () => 'https://example.com',
            setApiBaseUrlCallback: function (string $apiBaseUrl): void {
                $this->capturedApiBaseUrl = $apiBaseUrl;
            },
            setBaseUrlCallback: function (string $baseUrl): void {
                $this->capturedBaseUrl = $baseUrl;
            }
        );
    }

    public function testGetApiBaseUrlDelegatesToCallback(): void
    {
        static::assertSame('https://api.example.com', $this->proxyConfiguration->getApiBaseUrl());
    }

    public function testGetApiBaseUrlReturnsValueFromCallback(): void
    {
        $proxyConfiguration = new ProxyConfiguration(
            getApiBaseUrlCallback: fn () => 'https://my-api.test',
            getBaseUrlCallback: fn () => '',
            setApiBaseUrlCallback: fn (string $url) => null,
            setBaseUrlCallback: fn (string $url) => null
        );

        static::assertSame('https://my-api.test', $proxyConfiguration->getApiBaseUrl());
    }

    public function testGetApiBaseUrlCallsCallbackEachTime(): void
    {
        $callCount = 0;
        $proxyConfiguration = new ProxyConfiguration(
            getApiBaseUrlCallback: function () use (&$callCount): string {
                $callCount++;
                return 'https://api.example.com';
            },
            getBaseUrlCallback: fn () => '',
            setApiBaseUrlCallback: fn (string $url) => null,
            setBaseUrlCallback: fn (string $url) => null
        );

        $proxyConfiguration->getApiBaseUrl();
        $proxyConfiguration->getApiBaseUrl();

        static::assertSame(2, $callCount);
    }

    public function testGetBaseUrlDelegatesToCallback(): void
    {
        static::assertSame('https://example.com', $this->proxyConfiguration->getBaseUrl());
    }

    public function testGetBaseUrlReturnsValueFromCallback(): void
    {
        $proxyConfiguration = new ProxyConfiguration(
            getApiBaseUrlCallback: fn () => '',
            getBaseUrlCallback: fn () => 'https://my-app.test',
            setApiBaseUrlCallback: fn (string $url) => null,
            setBaseUrlCallback: fn (string $url) => null
        );

        static::assertSame('https://my-app.test', $proxyConfiguration->getBaseUrl());
    }

    public function testGetBaseUrlCallsCallbackEachTime(): void
    {
        $callCount = 0;
        $proxyConfiguration = new ProxyConfiguration(
            getApiBaseUrlCallback: fn () => '',
            getBaseUrlCallback: function () use (&$callCount): string {
                $callCount++;
                return 'https://example.com';
            },
            setApiBaseUrlCallback: fn (string $url) => null,
            setBaseUrlCallback: fn (string $url) => null
        );

        $proxyConfiguration->getBaseUrl();
        $proxyConfiguration->getBaseUrl();

        static::assertSame(2, $callCount);
    }

    public function testSetApiBaseUrlDelegatesToCallback(): void
    {
        $this->proxyConfiguration->setApiBaseUrl('https://new-api.example.com');

        static::assertSame('https://new-api.example.com', $this->capturedApiBaseUrl);
    }

    public function testSetApiBaseUrlPassesUrlToCallback(): void
    {
        $receivedUrl = null;
        $proxyConfiguration = new ProxyConfiguration(
            getApiBaseUrlCallback: fn () => '',
            getBaseUrlCallback: fn () => '',
            setApiBaseUrlCallback: function (string $apiBaseUrl) use (&$receivedUrl): void {
                $receivedUrl = $apiBaseUrl;
            },
            setBaseUrlCallback: fn (string $url) => null
        );

        $proxyConfiguration->setApiBaseUrl('https://set-api.test');

        static::assertSame('https://set-api.test', $receivedUrl);
    }

    public function testSetBaseUrlDelegatesToCallback(): void
    {
        $this->proxyConfiguration->setBaseUrl('https://new.example.com');

        static::assertSame('https://new.example.com', $this->capturedBaseUrl);
    }

    public function testSetBaseUrlPassesUrlToCallback(): void
    {
        $receivedUrl = null;
        $proxyConfiguration = new ProxyConfiguration(
            getApiBaseUrlCallback: fn () => '',
            getBaseUrlCallback: fn () => '',
            setApiBaseUrlCallback: fn (string $url) => null,
            setBaseUrlCallback: function (string $baseUrl) use (&$receivedUrl): void {
                $receivedUrl = $baseUrl;
            }
        );

        $proxyConfiguration->setBaseUrl('https://set-app.test');

        static::assertSame('https://set-app.test', $receivedUrl);
    }
}
