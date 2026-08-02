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
use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Standard\Assertion\ArrangementAssertion;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ArrangementAssertionTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ArrangementAssertionTest extends AbstractTestCase
{
    private ArrangementInterface&MockInterface $arrangement;
    private ArrangementAssertion $assertion;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->arrangement = mock(ArrangementInterface::class);
        $this->environment = mock(EnvironmentInterface::class);

        $this->assertion = new ArrangementAssertion($this->arrangement);
    }

    public function testGetArrangementReturnsArrangement(): void
    {
        static::assertSame($this->arrangement, $this->assertion->getArrangement());
    }

    public function testPerformDelegatesToArrangementPerform(): void
    {
        $this->arrangement->expects()
            ->perform($this->environment)
            ->once();

        $this->assertion->perform($this->environment);
    }
}
