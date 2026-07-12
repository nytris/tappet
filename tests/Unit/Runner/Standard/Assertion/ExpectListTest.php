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
use Tappet\Runner\Standard\Assertion\ExpectList;
use Tappet\Runner\Standard\Matcher\Text;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExpectListTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectListTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private ExpectList $assertion;
    private Text $firstItemMatcher;
    private Text $secondItemMatcher;
    private Text $thirdItemMatcher;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->firstItemMatcher = new Text('First item');
        $this->secondItemMatcher = new Text('Second item');
        $this->thirdItemMatcher = new Text('Third item');

        $this->assertion = new ExpectList('recent-items', [
            $this->firstItemMatcher,
            $this->secondItemMatcher,
            $this->thirdItemMatcher,
        ]);
    }

    public function testGetItemsReturnsExpectedItems(): void
    {
        static::assertSame(
            [$this->firstItemMatcher, $this->secondItemMatcher, $this->thirdItemMatcher],
            $this->assertion->getItems()
        );
    }

    public function testGetRegionHandleReturnsRegionHandle(): void
    {
        static::assertSame('recent-items', $this->assertion->getRegionHandle());
    }

    public function testPerformDelegatesToEnvironmentPerformRegionAssertion(): void
    {
        $this->environment->expects()
            ->performRegionAssertion($this->assertion)
            ->once();

        $this->assertion->perform($this->environment);
    }
}
