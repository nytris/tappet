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

namespace Tappet\Tests\Unit\Runner\Standard\Action;

use Mockery\MockInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Standard\Action\Upload;
use Tappet\Tests\AbstractTestCase;

/**
 * Class UploadTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class UploadTest extends AbstractTestCase
{
    private Upload $action;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new Upload('avatar', '/tmp/photo.png');
    }

    public function testGetFieldHandleReturnsFieldHandle(): void
    {
        static::assertSame('avatar', $this->action->getFieldHandle());
    }

    public function testGetFilePathReturnsFilePath(): void
    {
        static::assertSame('/tmp/photo.png', $this->action->getFilePath());
    }

    public function testPerformDelegatesToEnvironmentPerformFieldAction(): void
    {
        $this->environment->expects()
            ->performFieldAction($this->action)
            ->once();

        $this->action->perform($this->environment);
    }
}
