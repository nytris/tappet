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

namespace Tappet\Tests\Unit\Runner\Automation\Matcher;

use InvalidArgumentException;
use Mockery\MockInterface;
use Tappet\Runner\Automation\AutomationInterface;
use Tappet\Runner\Automation\Matcher\MatchHandlerInterface;
use Tappet\Runner\Automation\Matcher\MatcherRegistry;
use Tappet\Runner\Matcher\ContextInterface;
use Tappet\Runner\Matcher\MatcherInterface;
use Tappet\Runner\Standard\Matcher\ExactText;
use Tappet\Runner\Standard\Matcher\Text;
use Tappet\Tests\AbstractTestCase;

/**
 * Class MatcherRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MatcherRegistryTest extends AbstractTestCase
{
    private AutomationInterface&MockInterface $automation;
    private ContextInterface&MockInterface $context;
    /** @var MatcherRegistry<AutomationInterface, ContextInterface> */
    private MatcherRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->automation = mock(AutomationInterface::class);
        $this->context = mock(ContextInterface::class);

        $this->registry = new MatcherRegistry();
    }

    public function testHandleMatcherDispatchesToRegisteredHandlerCallable(): void
    {
        $matcher = new Text('some text');
        $receivedMatcher = null;
        $receivedContext = null;
        $receivedAutomation = null;
        $this->registry->registerMatchHandler('default', mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                Text::class => function (MatcherInterface $m, ContextInterface $c, AutomationInterface $a) use (
                    &$receivedMatcher,
                    &$receivedContext,
                    &$receivedAutomation
                ): void {
                    $receivedMatcher = $m;
                    $receivedContext = $c;
                    $receivedAutomation = $a;
                },
            ],
        ]));

        $this->registry->handleMatcher('default', $matcher, $this->context, $this->automation);

        static::assertSame($matcher, $receivedMatcher);
        static::assertSame($this->context, $receivedContext);
        static::assertSame($this->automation, $receivedAutomation);
    }

    public function testHandleMatcherThrowsWhenNoHandlerRegisteredForMatcherType(): void
    {
        $matcher = new Text('some text');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No matcher handler registered for matcher type "default"');

        $this->registry->handleMatcher('default', $matcher, $this->context, $this->automation);
    }

    public function testHandleMatcherThrowsWhenHandlerDoesNotSupportMatcher(): void
    {
        $matcher = new Text('some text');
        $handler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerMatchHandler('default', $handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Matcher handler for matcher type "default" does not support matcher "%s"',
                Text::class
            )
        );

        $this->registry->handleMatcher('default', $matcher, $this->context, $this->automation);
    }

    public function testRegisterMatchHandlerOverwritesPreviousHandlerForSameMatcherType(): void
    {
        $matcher = new Text('some text');
        $firstHandlerCalled = false;
        $secondHandlerCalled = false;
        $firstHandler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                Text::class => function () use (&$firstHandlerCalled): void {
                    $firstHandlerCalled = true;
                },
            ],
        ]);
        $secondHandler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                Text::class => function () use (&$secondHandlerCalled): void {
                    $secondHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerMatchHandler('default', $firstHandler);

        $this->registry->registerMatchHandler('default', $secondHandler);
        $this->registry->handleMatcher('default', $matcher, $this->context, $this->automation);

        static::assertFalse($firstHandlerCalled);
        static::assertTrue($secondHandlerCalled);
    }

    public function testHandleMatcherSupportsMultipleMatcherTypes(): void
    {
        $matcher = new Text('some text');
        $defaultHandlerCalled = false;
        $badgeHandlerCalled = false;
        $defaultHandler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                Text::class => function () use (&$defaultHandlerCalled): void {
                    $defaultHandlerCalled = true;
                },
            ],
        ]);
        $badgeHandler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                Text::class => function () use (&$badgeHandlerCalled): void {
                    $badgeHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerMatchHandler('default', $defaultHandler);
        $this->registry->registerMatchHandler('badge', $badgeHandler);

        $this->registry->handleMatcher('default', $matcher, $this->context, $this->automation);

        static::assertTrue($defaultHandlerCalled);
        static::assertFalse($badgeHandlerCalled);
    }

    public function testRegisterMatchHandlerSupportsMultipleMatcherClassesFromOneHandler(): void
    {
        $textMatcher = new Text('some text');
        $exactTextMatcher = new ExactText('some text');
        $textHandlerCalled = false;
        $exactTextHandlerCalled = false;
        $handler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                Text::class => function () use (&$textHandlerCalled): void {
                    $textHandlerCalled = true;
                },
                ExactText::class => function () use (&$exactTextHandlerCalled): void {
                    $exactTextHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerMatchHandler('default', $handler);

        $this->registry->handleMatcher('default', $textMatcher, $this->context, $this->automation);

        static::assertTrue($textHandlerCalled);
        static::assertFalse($exactTextHandlerCalled);

        $this->registry->handleMatcher('default', $exactTextMatcher, $this->context, $this->automation);

        static::assertTrue($exactTextHandlerCalled);
    }

    public function testRegisterMatchHandlerMergesHandlersFromMultipleRegistrationsForSameMatcherType(): void
    {
        $textMatcher = new Text('some text');
        $exactTextMatcher = new ExactText('some text');
        $textHandlerCalled = false;
        $exactTextHandlerCalled = false;
        $textHandler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                Text::class => function () use (&$textHandlerCalled): void {
                    $textHandlerCalled = true;
                },
            ],
        ]);
        $exactTextHandler = mock(MatchHandlerInterface::class, [
            'getHandlers' => [
                ExactText::class => function () use (&$exactTextHandlerCalled): void {
                    $exactTextHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerMatchHandler('default', $textHandler);
        $this->registry->registerMatchHandler('default', $exactTextHandler);

        $this->registry->handleMatcher('default', $textMatcher, $this->context, $this->automation);
        $this->registry->handleMatcher('default', $exactTextMatcher, $this->context, $this->automation);

        static::assertTrue($textHandlerCalled);
        static::assertTrue($exactTextHandlerCalled);
    }
}
