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
use Tappet\Runner\Standard\Action\Select;
use Tappet\Tests\AbstractTestCase;

/**
 * Class SelectTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class SelectTest extends AbstractTestCase
{
    private Select $action;
    private EnvironmentInterface&MockInterface $environment;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new Select('country', 'gb');
    }

    public function testGetFieldHandleReturnsFieldHandle(): void
    {
        static::assertSame('country', $this->action->getFieldHandle());
    }

    public function testGetOptionValueReturnsOptionValue(): void
    {
        static::assertSame('gb', $this->action->getOptionValue());
    }

    public function testPerformDelegatesToEnvironmentPerformFieldAction(): void
    {
        $this->environment->expects()
            ->performFieldAction($this->action)
            ->once();

        $this->action->perform($this->environment);
    }
}
