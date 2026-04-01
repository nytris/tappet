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

namespace Tappet\Tests\Unit\Core\Standard\Action;

use Mockery\MockInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Standard\Action\Type;
use Tappet\Tests\AbstractTestCase;

/**
 * Class TypeTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TypeTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private Type $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new Type('username', 'hello world');
    }

    public function testGetFieldHandleReturnsFieldHandle(): void
    {
        static::assertSame('username', $this->action->getFieldHandle());
    }

    public function testGetTextReturnsText(): void
    {
        static::assertSame('hello world', $this->action->getText());
    }

    public function testPerformDelegatesToEnvironmentPerformFieldAction(): void
    {
        $this->environment->expects()
            ->performFieldAction($this->action)
            ->once();

        $this->action->perform($this->environment);
    }
}
