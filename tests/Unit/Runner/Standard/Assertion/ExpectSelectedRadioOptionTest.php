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
use Tappet\Runner\Standard\Assertion\ExpectSelectedRadioOption;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExpectSelectedRadioOptionTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectSelectedRadioOptionTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private ExpectSelectedRadioOption $assertion;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->assertion = new ExpectSelectedRadioOption('status', 'active');
    }

    public function testGetFieldHandleReturnsFieldHandle(): void
    {
        static::assertSame('status', $this->assertion->getFieldHandle());
    }

    public function testGetValueReturnsExpectedValue(): void
    {
        static::assertSame('active', $this->assertion->getValue());
    }

    public function testPerformDelegatesToEnvironmentPerformFieldAssertion(): void
    {
        $this->environment->expects()
            ->performFieldAssertion($this->assertion)
            ->once();

        $this->assertion->perform($this->environment);
    }
}
