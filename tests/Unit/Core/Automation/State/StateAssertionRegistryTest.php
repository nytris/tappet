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

namespace Tappet\Tests\Unit\Core\Automation\State;

use InvalidArgumentException;
use Tappet\Core\Assertion\StateAssertionInterface;
use Tappet\Core\Automation\State\StateAssertionHandlerInterface;
use Tappet\Core\Automation\State\StateAssertionRegistry;
use Tappet\Core\Standard\Assertion\ExpectState;
use Tappet\Tests\AbstractTestCase;

/**
 * Class StateAssertionRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class StateAssertionRegistryTest extends AbstractTestCase
{
    private StateAssertionRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new StateAssertionRegistry();
    }

    public function testHandleStateAssertionDispatchesToRegisteredHandlerCallable(): void
    {
        $assertion = new ExpectState('my-state');
        $receivedAssertion = null;
        $this->registry->registerStateAssertionHandler('existent', mock(StateAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectState::class => function (StateAssertionInterface $a) use (&$receivedAssertion): void {
                    $receivedAssertion = $a;
                },
            ],
        ]));

        $this->registry->handleStateAssertion('existent', $assertion);

        static::assertSame($assertion, $receivedAssertion);
    }

    public function testHandleStateAssertionThrowsWhenNoHandlerRegisteredForStateType(): void
    {
        $assertion = new ExpectState('my-state');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No state assertion handler registered for state type "existent".');

        $this->registry->handleStateAssertion('existent', $assertion);
    }

    public function testHandleStateAssertionThrowsWhenHandlerDoesNotSupportAssertionType(): void
    {
        $assertion = new ExpectState('my-state');
        $handler = mock(StateAssertionHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerStateAssertionHandler('existent', $handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'State assertion handler for state type "existent" does not support assertion type "%s".',
                ExpectState::class
            )
        );

        $this->registry->handleStateAssertion('existent', $assertion);
    }

    public function testRegisterStateAssertionHandlerOverwritesPreviousHandlerForSameStateType(): void
    {
        $assertion = new ExpectState('my-state');
        $firstHandlerCalled = false;
        $secondHandlerCalled = false;
        $firstHandler = mock(StateAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectState::class => function () use (&$firstHandlerCalled): void {
                    $firstHandlerCalled = true;
                },
            ],
        ]);
        $secondHandler = mock(StateAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectState::class => function () use (&$secondHandlerCalled): void {
                    $secondHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerStateAssertionHandler('existent', $firstHandler);

        $this->registry->registerStateAssertionHandler('existent', $secondHandler);
        $this->registry->handleStateAssertion('existent', $assertion);

        static::assertFalse($firstHandlerCalled);
        static::assertTrue($secondHandlerCalled);
    }

    public function testHandleStateAssertionSupportsMultipleStateTypes(): void
    {
        $assertion = new ExpectState('my-state');
        $existentHandlerCalled = false;
        $visibleHandlerCalled = false;
        $existentHandler = mock(StateAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectState::class => function () use (&$existentHandlerCalled): void {
                    $existentHandlerCalled = true;
                },
            ],
        ]);
        $visibleHandler = mock(StateAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectState::class => function () use (&$visibleHandlerCalled): void {
                    $visibleHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerStateAssertionHandler('existent', $existentHandler);
        $this->registry->registerStateAssertionHandler('visible', $visibleHandler);

        $this->registry->handleStateAssertion('existent', $assertion);

        static::assertTrue($existentHandlerCalled);
        static::assertFalse($visibleHandlerCalled);
    }
}
