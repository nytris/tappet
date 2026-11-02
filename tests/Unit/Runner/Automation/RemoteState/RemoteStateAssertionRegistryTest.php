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

namespace Tappet\Tests\Unit\Runner\Automation\RemoteState;

use Tappet\Common\Exception\InvalidArgumentException;
use Tappet\Runner\Assertion\RemoteStateAssertionInterface;
use Tappet\Runner\Automation\RemoteState\RemoteStateAssertionHandlerInterface;
use Tappet\Runner\Automation\RemoteState\RemoteStateAssertionRegistry;
use Tappet\Tests\AbstractTestCase;

/**
 * Class RemoteStateAssertionRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RemoteStateAssertionRegistryTest extends AbstractTestCase
{
    private RemoteStateAssertionRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new RemoteStateAssertionRegistry();
    }

    public function testHandleRemoteStateAssertionDispatchesToRegisteredHandlerCallable(): void
    {
        $assertion = mock(RemoteStateAssertionInterface::class);
        $receivedAssertion = null;
        $this->registry->registerRemoteStateAssertionHandler(mock(RemoteStateAssertionHandlerInterface::class, [
            'getHandlers' => [
                $assertion::class => function (RemoteStateAssertionInterface $a) use (&$receivedAssertion): void {
                    $receivedAssertion = $a;
                },
            ],
        ]));

        $this->registry->handleRemoteStateAssertion($assertion);

        static::assertSame($assertion, $receivedAssertion);
    }

    public function testHandleRemoteStateAssertionThrowsWhenHandlerDoesNotSupportAssertionType(): void
    {
        $assertion = mock(RemoteStateAssertionInterface::class);
        $handler = mock(RemoteStateAssertionHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerRemoteStateAssertionHandler($handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'No remote state assertion handler registered for assertion class "%s"',
                $assertion::class
            )
        );

        $this->registry->handleRemoteStateAssertion($assertion);
    }

    public function testRegisterRemoteStateAssertionHandlerMergesHandlersFromMultipleRegistrations(): void
    {
        $assertion = mock(RemoteStateAssertionInterface::class);
        $otherAssertion = mock(TestRemoteStateAssertionInterface::class);
        $expectStateHandlerCalled = false;
        $otherHandlerCalled = false;
        $expectStateHandler = mock(RemoteStateAssertionHandlerInterface::class, [
            'getHandlers' => [
                $assertion::class => function () use (&$expectStateHandlerCalled): void {
                    $expectStateHandlerCalled = true;
                },
            ],
        ]);
        $otherHandler = mock(RemoteStateAssertionHandlerInterface::class, [
            'getHandlers' => [
                $otherAssertion::class => function () use (&$otherHandlerCalled): void {
                    $otherHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerRemoteStateAssertionHandler($expectStateHandler);
        $this->registry->registerRemoteStateAssertionHandler($otherHandler);

        $this->registry->handleRemoteStateAssertion($assertion);
        $this->registry->handleRemoteStateAssertion($otherAssertion);

        static::assertTrue($expectStateHandlerCalled);
        static::assertTrue($otherHandlerCalled);
    }
}

interface TestRemoteStateAssertionInterface extends RemoteStateAssertionInterface {}
