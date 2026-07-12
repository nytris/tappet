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
use Tappet\Runner\Standard\Action\Uncheck;
use Tappet\Tests\AbstractTestCase;

/**
 * Class UncheckTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class UncheckTest extends AbstractTestCase
{
    private Uncheck $action;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new Uncheck('subscribe');
    }

    public function testGetFieldHandleReturnsFieldHandle(): void
    {
        static::assertSame('subscribe', $this->action->getFieldHandle());
    }

    public function testPerformDelegatesToEnvironmentPerformFieldAction(): void
    {
        $this->environment->expects()
            ->performFieldAction($this->action)
            ->once();

        $this->action->perform($this->environment);
    }
}
