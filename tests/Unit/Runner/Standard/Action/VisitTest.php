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

namespace Tappet\Tests\Unit\Runner\Standard\Action;

use Mockery\MockInterface;
use Tappet\Runner\Environment\EnvironmentInterface;
use Tappet\Runner\Page\PageInterface;
use Tappet\Runner\Standard\Action\Visit;
use Tappet\Tests\AbstractTestCase;

/**
 * Class VisitTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class VisitTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private PageInterface&MockInterface $page;
    private Visit $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
        $this->page = mock(PageInterface::class);

        $this->action = new Visit($this->page);
    }

    public function testGetPageReturnsPage(): void
    {
        static::assertSame($this->page, $this->action->getPage());
    }

    public function testPerformVisitsThePage(): void
    {
        $this->environment->expects()
            ->visitPage($this->page)
            ->once();

        $this->action->perform($this->environment);
    }
}
