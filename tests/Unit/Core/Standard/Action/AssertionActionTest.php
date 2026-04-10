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
use Tappet\Core\Assertion\AssertionInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Standard\Action\AssertionAction;
use Tappet\Tests\AbstractTestCase;

/**
 * Class AssertionActionTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class AssertionActionTest extends AbstractTestCase
{
    private AssertionInterface&MockInterface $assertion;
    private EnvironmentInterface&MockInterface $environment;
    private AssertionAction $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->assertion = mock(AssertionInterface::class);
        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new AssertionAction($this->assertion);
    }

    public function testGetAssertionReturnsAssertion(): void
    {
        static::assertSame($this->assertion, $this->action->getAssertion());
    }

    public function testPerformDelegatesToAssertionPerform(): void
    {
        $this->assertion->expects()
            ->perform($this->environment)
            ->once();

        $this->action->perform($this->environment);
    }
}
