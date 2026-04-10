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
use Tappet\Core\Standard\Assertion\ExpectRegionDoesNotContain;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExpectRegionDoesNotContainTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectRegionDoesNotContainTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private ExpectRegionDoesNotContain $assertion;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->assertion = new ExpectRegionDoesNotContain('flash-message', 'Something went wrong.');
    }

    public function testGetRegionHandleReturnsRegionHandle(): void
    {
        static::assertSame('flash-message', $this->assertion->getRegionHandle());
    }

    public function testGetTextReturnsExpectedText(): void
    {
        static::assertSame('Something went wrong.', $this->assertion->getText());
    }

    public function testPerformDelegatesToEnvironmentPerformRegionAssertion(): void
    {
        $this->environment->expects()
            ->performRegionAssertion($this->assertion)
            ->once();

        $this->assertion->perform($this->environment);
    }
}
