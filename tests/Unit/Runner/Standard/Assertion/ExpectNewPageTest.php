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

use Mockery;
use Mockery\MockInterface;
use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Assertion\AssertionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Page\PageInterface;
use Tappet\Runner\Standard\Assertion\ExpectNewPage;
use Tappet\Runner\Transition\PageTransition;
use Tappet\Runner\Transition\TransitionInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExpectNewPageTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExpectNewPageTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private PageInterface&MockInterface $page;
    private ExpectNewPage $assertion;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
        $this->page = mock(PageInterface::class);

        $this->assertion = new ExpectNewPage($this->page);
    }

    public function testImplementsArrangementInterface(): void
    {
        static::assertInstanceOf(ArrangementInterface::class, $this->assertion);
    }

    public function testImplementsAssertionInterface(): void
    {
        static::assertInstanceOf(AssertionInterface::class, $this->assertion);
    }

    public function testGetPageReturnsPage(): void
    {
        static::assertSame($this->page, $this->assertion->getPage());
    }

    public function testPerformCallsAssertTransitionWithAPageTransitionForThePage(): void
    {
        $this->environment->allows()
            ->buildPageUrl($this->page)
            ->andReturn('https://example.com/dashboard');

        $this->environment->expects()
            ->assertTransition(Mockery::on(function (TransitionInterface $transition) {
                return $transition instanceof PageTransition
                    && $transition->getUrl() === 'https://example.com/dashboard';
            }))
            ->once();

        $this->assertion->perform($this->environment);
    }
}
