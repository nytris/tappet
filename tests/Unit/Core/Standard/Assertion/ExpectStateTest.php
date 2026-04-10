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

namespace Tappet\Tests\Unit\Core\Standard\Assertion;

use Mockery\MockInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Standard\Assertion\ExpectState;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExpectStateTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectStateTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private ExpectState $assertion;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->assertion = new ExpectState('modal-open');
    }

    public function testGetStateHandleReturnsStateHandle(): void
    {
        static::assertSame('modal-open', $this->assertion->getStateHandle());
    }

    public function testPerformDelegatesToEnvironmentPerformStateAssertion(): void
    {
        $this->environment->expects()
            ->performStateAssertion($this->assertion)
            ->once();

        $this->assertion->perform($this->environment);
    }
}
