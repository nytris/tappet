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

namespace Tappet\Tests\Unit\Cli\Implementation;

use Mockery\MockInterface;
use Tappet\Cli\Bin\TappetBinaryInterface;
use Tappet\Cli\Config\ConfigInterface;
use Tappet\Cli\Implementation\DefaultImplementation;
use Tappet\Tests\AbstractTestCase;

/**
 * Class DefaultImplementationTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class DefaultImplementationTest extends AbstractTestCase
{
    private ConfigInterface&MockInterface $config;
    private DefaultImplementation $implementation;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = mock(ConfigInterface::class);

        $this->implementation = new DefaultImplementation($this->config);
    }

    public function testCreateTappetBinaryReturnsTheTappetBinary(): void
    {
        static::assertInstanceOf(
            TappetBinaryInterface::class,
            $this->implementation->createTappetBinary('/my/config/root', '/my/project/root')
        );
    }
}
