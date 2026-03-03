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

namespace Tappet\Tests\Unit\Core\Standard\Arrangement;

use Mockery\MockInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Page\PageInterface;
use Tappet\Core\Standard\Arrangement\OpenPage;
use Tappet\Tests\AbstractTestCase;

/**
 * Class OpenPageTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class OpenPageTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private PageInterface&MockInterface $page;
    private OpenPage $arrangement;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
        $this->page = mock(PageInterface::class);

        $this->arrangement = new OpenPage($this->page);
    }

    public function testGetPageReturnsPage(): void
    {
        static::assertSame($this->page, $this->arrangement->getPage());
    }

    public function testPerformCallsVisitPageWithBuiltUrl(): void
    {
        $this->page->allows('buildUrl')
            ->with($this->environment)
            ->andReturn('https://example.com/login');
        $this->environment->expects()
            ->visitPage('https://example.com/login')
            ->once();

        $this->arrangement->perform($this->environment);
    }

    public function testPerformBuildsUrlFromPage(): void
    {
        $this->page->expects()
            ->buildUrl($this->environment)
            ->once()
            ->andReturn('https://example.com/dashboard');
        $this->environment->allows('visitPage');

        $this->arrangement->perform($this->environment);
    }
}
