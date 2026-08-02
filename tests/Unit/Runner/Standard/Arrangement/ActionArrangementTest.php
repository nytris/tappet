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

namespace Tappet\Tests\Unit\Runner\Standard\Arrangement;

use Mockery\MockInterface;
use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Standard\Arrangement\ActionArrangement;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ActionArrangementTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ActionArrangementTest extends AbstractTestCase
{
    private ActionInterface&MockInterface $action;
    private ActionArrangement $arrangement;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = mock(ActionInterface::class);
        $this->environment = mock(EnvironmentInterface::class);

        $this->arrangement = new ActionArrangement($this->action);
    }

    public function testGetActionReturnsAction(): void
    {
        static::assertSame($this->action, $this->arrangement->getAction());
    }

    public function testPerformDelegatesToActionPerform(): void
    {
        $this->action->expects()
            ->perform($this->environment)
            ->once();

        $this->arrangement->perform($this->environment);
    }
}
