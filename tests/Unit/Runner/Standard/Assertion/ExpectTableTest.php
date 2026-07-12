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
use Tappet\Runner\Standard\Assertion\ExpectTable;
use Tappet\Runner\Standard\Matcher\Text;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExpectTableTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectTableTest extends AbstractTestCase
{
    private Text $aliceEmailMatcher;
    private Text $aliceNameMatcher;
    private ExpectTable $assertion;
    private EnvironmentInterface&MockInterface $environment;
    private Text $bobEmailMatcher;
    private Text $bobNameMatcher;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->aliceNameMatcher = new Text('Alice');
        $this->aliceEmailMatcher = new Text('alice@example.com');
        $this->bobNameMatcher = new Text('Bob');
        $this->bobEmailMatcher = new Text('bob@example.com');

        $this->assertion = new ExpectTable('users', [
            ['name' => $this->aliceNameMatcher, 'email' => $this->aliceEmailMatcher],
            ['name' => $this->bobNameMatcher, 'email' => $this->bobEmailMatcher],
        ]);
    }

    public function testGetRegionHandleReturnsRegionHandle(): void
    {
        static::assertSame('users', $this->assertion->getRegionHandle());
    }

    public function testGetRowsReturnsExpectedRows(): void
    {
        static::assertSame([
            ['name' => $this->aliceNameMatcher, 'email' => $this->aliceEmailMatcher],
            ['name' => $this->bobNameMatcher, 'email' => $this->bobEmailMatcher],
        ], $this->assertion->getRows());
    }

    public function testPerformDelegatesToEnvironmentPerformRegionAssertion(): void
    {
        $this->environment->expects()
            ->performRegionAssertion($this->assertion)
            ->once();

        $this->assertion->perform($this->environment);
    }
}
