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
use Tappet\Core\Action\InteractionInterface;
use Tappet\Core\Automation\Interaction\InteractionHandlerInterface;
use Tappet\Core\Automation\Interaction\InteractionRegistry;
use Tappet\Core\Standard\Action\PerformInteraction;
use Tappet\Tests\AbstractTestCase;

/**
 * Class InteractionRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class InteractionRegistryTest extends AbstractTestCase
{
    private InteractionRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new InteractionRegistry();
    }

    public function testHandleInteractionDispatchesToRegisteredHandlerCallable(): void
    {
        $interaction = new PerformInteraction('my-button');
        $receivedInteraction = null;
        $this->registry->registerInteractionHandler('button', mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                PerformInteraction::class => function (InteractionInterface $i) use (&$receivedInteraction): void {
                    $receivedInteraction = $i;
                },
            ],
        ]));

        $this->registry->handleInteraction('button', $interaction);

        static::assertSame($interaction, $receivedInteraction);
    }

    public function testHandleInteractionThrowsWhenNoHandlerRegisteredForInteractionType(): void
    {
        $interaction = new PerformInteraction('my-button');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No interaction handler registered for interaction type "button".');

        $this->registry->handleInteraction('button', $interaction);
    }

    public function testHandleInteractionThrowsWhenHandlerDoesNotSupportInteractionClass(): void
    {
        $interaction = new PerformInteraction('my-button');
        $handler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerInteractionHandler('button', $handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Interaction handler for interaction type "button" does not support interaction class "%s".',
                PerformInteraction::class
            )
        );

        $this->registry->handleInteraction('button', $interaction);
    }

    public function testRegisterInteractionHandlerOverwritesPreviousHandlerForSameInteractionType(): void
    {
        $interaction = new PerformInteraction('my-button');
        $firstHandlerCalled = false;
        $secondHandlerCalled = false;
        $firstHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                PerformInteraction::class => function () use (&$firstHandlerCalled): void {
                    $firstHandlerCalled = true;
                },
            ],
        ]);
        $secondHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                PerformInteraction::class => function () use (&$secondHandlerCalled): void {
                    $secondHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerInteractionHandler('button', $firstHandler);

        $this->registry->registerInteractionHandler('button', $secondHandler);
        $this->registry->handleInteraction('button', $interaction);

        static::assertFalse($firstHandlerCalled);
        static::assertTrue($secondHandlerCalled);
    }

    public function testHandleInteractionSupportsMultipleInteractionTypes(): void
    {
        $interaction = new PerformInteraction('my-button');
        $buttonHandlerCalled = false;
        $linkHandlerCalled = false;
        $buttonHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                PerformInteraction::class => function () use (&$buttonHandlerCalled): void {
                    $buttonHandlerCalled = true;
                },
            ],
        ]);
        $linkHandler = mock(InteractionHandlerInterface::class, [
            'getHandlers' => [
                PerformInteraction::class => function () use (&$linkHandlerCalled): void {
                    $linkHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerInteractionHandler('button', $buttonHandler);
        $this->registry->registerInteractionHandler('link', $linkHandler);

        $this->registry->handleInteraction('button', $interaction);

        static::assertTrue($buttonHandlerCalled);
        static::assertFalse($linkHandlerCalled);
    }
}
