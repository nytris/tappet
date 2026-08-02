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

namespace Tappet\Tests\Unit\Runner\Standard\Assertion;

use Mockery\MockInterface;
use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Standard\Assertion\ActionAssertion;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ActionAssertionTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ActionAssertionTest extends AbstractTestCase
{
    private ActionInterface&MockInterface $action;
    private ActionAssertion $assertion;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->action = mock(ActionInterface::class);
        $this->environment = mock(EnvironmentInterface::class);

        $this->assertion = new ActionAssertion($this->action);
    }

    public function testGetActionReturnsAction(): void
    {
        static::assertSame($this->action, $this->assertion->getAction());
    }

    public function testPerformDelegatesToActionPerform(): void
    {
        $this->action->expects()
            ->perform($this->environment)
            ->once();

        $this->assertion->perform($this->environment);
    }
}
