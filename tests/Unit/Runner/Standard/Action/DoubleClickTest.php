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
use Tappet\Runner\Standard\Action\DoubleClick;
use Tappet\Tests\AbstractTestCase;

/**
 * Class DoubleClickTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class DoubleClickTest extends AbstractTestCase
{
    private DoubleClick $action;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new DoubleClick('editable-cell');
    }

    public function testGetInteractionHandleReturnsInteractionHandle(): void
    {
        static::assertSame('editable-cell', $this->action->getInteractionHandle());
    }

    public function testPerformDelegatesToEnvironmentPerformInteraction(): void
    {
        $this->environment->expects()
            ->performInteraction($this->action)
            ->once();

        $this->action->perform($this->environment);
    }
}
