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

namespace Tappet\Tests\Unit\Runner\Automation\Field;

use InvalidArgumentException;
use Tappet\Runner\Assertion\FieldAssertionInterface;
use Tappet\Runner\Automation\Field\FieldAssertionHandlerInterface;
use Tappet\Runner\Automation\Field\FieldAssertionRegistry;
use Tappet\Runner\Standard\Assertion\ExpectSelectedOption;
use Tappet\Runner\Standard\Assertion\ExpectTextFieldValue;
use Tappet\Tests\AbstractTestCase;

/**
 * Class FieldAssertionRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FieldAssertionRegistryTest extends AbstractTestCase
{
    private FieldAssertionRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new FieldAssertionRegistry();
    }

    public function testHandleFieldAssertionDispatchesToRegisteredHandlerCallable(): void
    {
        $assertion = new ExpectTextFieldValue('my-field', 'hello');
        $receivedAssertion = null;
        $this->registry->registerFieldAssertionHandler('text', mock(FieldAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectTextFieldValue::class => function (FieldAssertionInterface $assertion) use (&$receivedAssertion): void {
                    $receivedAssertion = $assertion;
                },
            ]
        ]));

        $this->registry->handleFieldAssertion('text', $assertion);

        static::assertSame($assertion, $receivedAssertion);
    }

    public function testHandleFieldAssertionThrowsWhenNoHandlerRegisteredForFieldType(): void
    {
        $assertion = mock(FieldAssertionInterface::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No field assertion handler registered for field type "text"');

        $this->registry->handleFieldAssertion('text', $assertion);
    }

    public function testHandleFieldAssertionThrowsWhenHandlerDoesNotSupportAssertionType(): void
    {
        $assertion = new ExpectTextFieldValue('my-field', 'hello');
        $handler = mock(FieldAssertionHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerFieldAssertionHandler('text', $handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Field assertion handler for field type "text" does not support assertion type "%s"',
                ExpectTextFieldValue::class
            )
        );

        $this->registry->handleFieldAssertion('text', $assertion);
    }

    public function testRegisterFieldAssertionHandlerOverwritesPreviousHandlerForSameFieldType(): void
    {
        $assertion = new ExpectTextFieldValue('my-field', 'hello');
        $firstHandlerCalled = false;
        $secondHandlerCalled = false;
        $firstHandler = mock(FieldAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectTextFieldValue::class => function () use (&$firstHandlerCalled): void {
                    $firstHandlerCalled = true;
                },
            ],
        ]);
        $secondHandler = mock(FieldAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectTextFieldValue::class => function () use (&$secondHandlerCalled): void {
                    $secondHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerFieldAssertionHandler('text', $firstHandler);

        $this->registry->registerFieldAssertionHandler('text', $secondHandler);
        $this->registry->handleFieldAssertion('text', $assertion);

        static::assertFalse($firstHandlerCalled);
        static::assertTrue($secondHandlerCalled);
    }

    public function testRegisterFieldAssertionHandlerMergesHandlersFromMultipleRegistrationsForSameFieldType(): void
    {
        $textAssertion = new ExpectTextFieldValue('my-field', 'hello');
        $selectedOptionAssertion = new ExpectSelectedOption('my-field', 'option-1');
        $textHandlerCalled = false;
        $selectedOptionHandlerCalled = false;
        $textHandler = mock(FieldAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectTextFieldValue::class => function () use (&$textHandlerCalled): void {
                    $textHandlerCalled = true;
                },
            ],
        ]);
        $selectedOptionHandler = mock(FieldAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectSelectedOption::class => function () use (&$selectedOptionHandlerCalled): void {
                    $selectedOptionHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerFieldAssertionHandler('text', $textHandler);
        $this->registry->registerFieldAssertionHandler('text', $selectedOptionHandler);

        $this->registry->handleFieldAssertion('text', $textAssertion);
        $this->registry->handleFieldAssertion('text', $selectedOptionAssertion);

        static::assertTrue($textHandlerCalled);
        static::assertTrue($selectedOptionHandlerCalled);
    }
}
