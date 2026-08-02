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
use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Standard\Action\ArrangementAction;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ArrangementActionTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ArrangementActionTest extends AbstractTestCase
{
    private ArrangementAction $action;
    private ArrangementInterface&MockInterface $arrangement;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->arrangement = mock(ArrangementInterface::class);
        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new ArrangementAction($this->arrangement);
    }

    public function testGetArrangementReturnsArrangement(): void
    {
        static::assertSame($this->arrangement, $this->action->getArrangement());
    }

    public function testPerformDelegatesToArrangementPerform(): void
    {
        $this->arrangement->expects()
            ->perform($this->environment)
            ->once();

        $this->action->perform($this->environment);
    }
}
