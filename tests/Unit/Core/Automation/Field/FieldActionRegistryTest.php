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

namespace Tappet\Tests\Unit\Core\Automation\Field;

use InvalidArgumentException;
use Tappet\Core\Action\FieldActionInterface;
use Tappet\Core\Automation\Field\FieldActionHandlerInterface;
use Tappet\Core\Automation\Field\FieldActionRegistry;
use Tappet\Core\Standard\Action\Type;
use Tappet\Tests\AbstractTestCase;

/**
 * Class FieldActionRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class FieldActionRegistryTest extends AbstractTestCase
{
    private FieldActionRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new FieldActionRegistry();
    }

    public function testHandleFieldActionDispatchesToRegisteredHandlerCallable(): void
    {
        $action = new Type('my-field', 'some text');
        $receivedAction = null;
        $this->registry->registerFieldActionHandler('text', mock(FieldActionHandlerInterface::class, [
            'getHandlers' => [
                Type::class => function (FieldActionInterface $a) use (&$receivedAction): void {
                    $receivedAction = $a;
                },
            ]
        ]));

        $this->registry->handleFieldAction('text', $action);

        static::assertSame($action, $receivedAction);
    }

    public function testHandleFieldActionThrowsWhenNoHandlerRegisteredForFieldType(): void
    {
        $action = mock(FieldActionInterface::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No field action handler registered for field type "text".');

        $this->registry->handleFieldAction('text', $action);
    }

    public function testHandleFieldActionThrowsWhenHandlerDoesNotSupportActionType(): void
    {
        $action = new Type('my-field', 'some text');
        $handler = mock(FieldActionHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerFieldActionHandler('text', $handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Field action handler for field type "text" does not support action type "%s".',
                Type::class
            )
        );

        $this->registry->handleFieldAction('text', $action);
    }

    public function testRegisterFieldActionHandlerOverwritesPreviousHandlerForSameFieldType(): void
    {
        $action = new Type('my-field', 'some text');
        $firstHandlerCalled = false;
        $secondHandlerCalled = false;
        $firstHandler = mock(FieldActionHandlerInterface::class, [
            'getHandlers' => [
                Type::class => function () use (&$firstHandlerCalled): void {
                    $firstHandlerCalled = true;
                },
            ],
        ]);
        $secondHandler = mock(FieldActionHandlerInterface::class, [
            'getHandlers' => [
                Type::class => function () use (&$secondHandlerCalled): void {
                    $secondHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerFieldActionHandler('text', $firstHandler);

        $this->registry->registerFieldActionHandler('text', $secondHandler);
        $this->registry->handleFieldAction('text', $action);

        static::assertFalse($firstHandlerCalled);
        static::assertTrue($secondHandlerCalled);
    }

    public function testHandleFieldActionSupportsMultipleFieldTypes(): void
    {
        $typeAction = new Type('my-field', 'some text');
        $typeHandlerCalled = false;
        $comboHandlerCalled = false;
        $textHandler = mock(FieldActionHandlerInterface::class, [
            'getHandlers' => [
                Type::class => function () use (&$typeHandlerCalled): void {
                    $typeHandlerCalled = true;
                },
            ],
        ]);
        $comboHandler = mock(FieldActionHandlerInterface::class, [
            'getHandlers' => [
                Type::class => function () use (&$comboHandlerCalled): void {
                    $comboHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerFieldActionHandler('text', $textHandler);
        $this->registry->registerFieldActionHandler('combobox', $comboHandler);

        $this->registry->handleFieldAction('text', $typeAction);

        static::assertTrue($typeHandlerCalled);
        static::assertFalse($comboHandlerCalled);
    }
}
