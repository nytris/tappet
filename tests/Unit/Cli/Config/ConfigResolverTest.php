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
use Tappet\Cli\Config\ConfigInterface;
use Tappet\Cli\Config\ConfigResolver;
use Tappet\Cli\Config\MissingConfig;
use Tappet\Core\Exception\InvalidConfigurationException;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ConfigResolverTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ConfigResolverTest extends AbstractTestCase
{
    private ConfigResolver $configResolver;
    private string $fixturesPath;

    public function setUp(): void
    {
        parent::setUp();

        $this->fixturesPath = __DIR__ . '/../../../Functional/Fixtures/TappetConfiguredApp';

        $this->configResolver = new ConfigResolver($this->fixturesPath);
    }

    public function testResolveConfigReturnsConfigInstance(): void
    {
        $config = $this->configResolver->resolveConfig();

        static::assertInstanceOf(Config::class, $config);
        static::assertSame('mysuite', $config->getDefaultSuite());
    }

    public function testResolveConfigLoadsLocalConfigFileWhenBothExist(): void
    {
        $configResolver = new ConfigResolver(dirname(__DIR__, 2) . '/Fixtures/ConfigResolver/local');

        $config = $configResolver->resolveConfig();

        static::assertInstanceOf(Config::class, $config);
        static::assertSame('fromlocal', $config->getDefaultSuite());
    }

    public function testResolveConfigReturnsMissingConfigWhenConfigFileDoesNotExist(): void
    {
        $configResolver = new ConfigResolver('/nonexistent/path');

        $config = $configResolver->resolveConfig();

        static::assertInstanceOf(MissingConfig::class, $config);
    }

    public function testResolveConfigThrowsWhenConfigFileDoesNotReturnConfigInstance(): void
    {
        $fixtureDir = dirname(__DIR__, 2) . '/Fixtures/ConfigResolver/invalid';
        $configResolver = new ConfigResolver($fixtureDir);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage(sprintf(
            'Return value of module %s is expected to be an instance of %s but was not',
            $fixtureDir . '/tappet.config.php',
            ConfigInterface::class
        ));

        $configResolver->resolveConfig();
    }
}
