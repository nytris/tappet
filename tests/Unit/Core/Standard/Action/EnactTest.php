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

namespace Tappet\Tests\Unit\Core\Standard\Action;

use Mockery\MockInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Standard\Action\Enact;
use Tappet\Tests\AbstractTestCase;

/**
 * Class EnactTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class EnactTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    private Enact $action;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);

        $this->action = new Enact('submit-button');
    }

    public function testGetInteractionHandleReturnsInteractionHandle(): void
    {
        static::assertSame('submit-button', $this->action->getInteractionHandle());
    }

    public function testPerformDelegatesToEnvironmentPerformInteraction(): void
    {
        $this->environment->expects()
            ->performInteraction($this->action)
            ->once();

        $this->action->perform($this->environment);
    }
}
