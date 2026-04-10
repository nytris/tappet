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

namespace Tappet\Tests\Unit\Core\Automation\Interaction;

use InvalidArgumentException;
use Mockery\MockInterface;
use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Automation\AutomationInterface;
use Tappet\Core\Automation\Interaction\InteractionHandlerInterface;
use Tappet\Core\Automation\Interaction\InteractionRegistry;
use Tappet\Core\Standard\Action\Enact;
use Tappet\Tests\AbstractTestCase;

/**
 * Class InteractionRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class InteractionRegistryTest extends AbstractTestCase
{
    private AutomationInterface&MockInterface $automation;
    private InteractionRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = mock(AutomationInterface::class);

        $this->registry = new InteractionRegistry();
    }

    public function testHandleInteractionDispatchesToRegisteredHandlerCallable(): void
    {
        $interaction = new Enact('my-button');
        $receivedInteraction = null;
        $this->registry->registerInteractionHandler('button', mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                Enact::class => function (InteractionInterface $i) use (&$receivedInteraction): void {
                    $receivedInteraction = $i;
                },
            ],
        ]));

        $this->registry->handleInteraction('button', $interaction, $this->automation);

        static::assertSame($interaction, $receivedInteraction);
    }

    public function testHandleInteractionThrowsWhenNoHandlerRegisteredForInteractionType(): void
    {
        $interaction = new Enact('my-button');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No interaction handler registered for interaction type "button".');

        $this->registry->handleInteraction('button', $interaction, $this->automation);
    }

    public function testHandleInteractionThrowsWhenHandlerDoesNotSupportInteractionClass(): void
    {
        $interaction = new Enact('my-button');
        $handler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerInteractionHandler('button', $handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Interaction handler for interaction type "button" does not support interaction class "%s".',
                Enact::class
            )
        );

        $this->registry->handleInteraction('button', $interaction, $this->automation);
    }

    public function testRegisterInteractionHandlerOverwritesPreviousHandlerForSameInteractionType(): void
    {
        $interaction = new Enact('my-button');
        $firstHandlerCalled = false;
        $secondHandlerCalled = false;
        $firstHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                Enact::class => function () use (&$firstHandlerCalled): void {
                    $firstHandlerCalled = true;
                },
            ],
        ]);
        $secondHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                Enact::class => function () use (&$secondHandlerCalled): void {
                    $secondHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerInteractionHandler('button', $firstHandler);

        $this->registry->registerInteractionHandler('button', $secondHandler);
        $this->registry->handleInteraction('button', $interaction, $this->automation);

        static::assertFalse($firstHandlerCalled);
        static::assertTrue($secondHandlerCalled);
    }

    public function testHandleInteractionSupportsMultipleInteractionTypes(): void
    {
        $interaction = new Enact('my-button');
        $buttonHandlerCalled = false;
        $linkHandlerCalled = false;
        $buttonHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                Enact::class => function () use (&$buttonHandlerCalled): void {
                    $buttonHandlerCalled = true;
                },
            ],
        ]);
        $linkHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                Enact::class => function () use (&$linkHandlerCalled): void {
                    $linkHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerInteractionHandler('button', $buttonHandler);
        $this->registry->registerInteractionHandler('link', $linkHandler);

        $this->registry->handleInteraction('button', $interaction, $this->automation);

        static::assertTrue($buttonHandlerCalled);
        static::assertFalse($linkHandlerCalled);
    }
}
