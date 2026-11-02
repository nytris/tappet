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
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Matcher\MatcherInterface;
use Tappet\Runner\Standard\Assertion\ExpectRemoteState;
use Tappet\Runner\Standard\Matcher\ExactText;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExpectRemoteStateTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectRemoteStateTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
    }

    public function testGetRemoteStateHandleReturnsRemoteStateHandle(): void
    {
        $assertion = new ExpectRemoteState('order-status', new ExactText('Shipped'));

        static::assertSame('order-status', $assertion->getRemoteStateHandle());
    }

    public function testGetMatcherReturnsGivenMatcherInstanceUnchanged(): void
    {
        $matcher = mock(MatcherInterface::class);
        $assertion = new ExpectRemoteState('order-status', $matcher);

        static::assertSame($matcher, $assertion->getMatcher());
    }

    public function testGetMatcherWrapsGivenStringInAnExactTextMatcher(): void
    {
        $assertion = new ExpectRemoteState('order-status', 'Shipped');

        $matcher = $assertion->getMatcher();

        static::assertInstanceOf(ExactText::class, $matcher);
        static::assertSame('Shipped', $matcher->getText());
    }

    public function testPerformDelegatesToEnvironmentPerformRemoteStateAssertion(): void
    {
        $assertion = new ExpectRemoteState('order-status', new ExactText('Shipped'));

        $this->environment->expects()
            ->performRemoteStateAssertion($assertion)
            ->once();

        $assertion->perform($this->environment);
    }
}
