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
use Tappet\Core\Page\PageInterface;
use Tappet\Core\Standard\Assertion\ExpectNewPage;
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

    public function testGetPageReturnsPage(): void
    {
        static::assertSame($this->page, $this->assertion->getPage());
    }

    public function testPerformCallsAssertPageWithBuiltUrl(): void
    {
        $this->page->allows('buildUrl')
            ->with($this->environment)
            ->andReturn('https://example.com/dashboard');

        $this->environment->expects()
            ->assertPage('https://example.com/dashboard')
            ->once();

        $this->assertion->perform($this->environment);
    }

    public function testPerformBuildsUrlFromPage(): void
    {
        $this->environment->allows('assertPage');

        $this->page->expects()
            ->buildUrl($this->environment)
            ->once()
            ->andReturn('https://example.com/login');

        $this->assertion->perform($this->environment);
    }
}
