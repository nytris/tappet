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

namespace Tappet\Tests\Unit\Core\Automation\Region;

use InvalidArgumentException;
use Tappet\Core\Assertion\RegionAssertionInterface;
use Tappet\Core\Automation\Region\RegionAssertionHandlerInterface;
use Tappet\Core\Automation\Region\RegionAssertionRegistry;
use Tappet\Core\Standard\Assertion\ExpectRegionContains;
use Tappet\Core\Standard\Assertion\ExpectRegionDoesNotContain;
use Tappet\Tests\AbstractTestCase;

/**
 * Class RegionAssertionRegistryTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RegionAssertionRegistryTest extends AbstractTestCase
{
    private RegionAssertionRegistry $registry;

    public function setUp(): void
    {
        parent::setUp();

        $this->registry = new RegionAssertionRegistry();
    }

    public function testHandleRegionAssertionDispatchesToRegisteredHandlerCallable(): void
    {
        $assertion = new ExpectRegionContains('my-region', 'some text');
        $receivedAssertion = null;
        $this->registry->registerRegionAssertionHandler('sidebar', mock(RegionAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectRegionContains::class => function (RegionAssertionInterface $a) use (&$receivedAssertion): void {
                    $receivedAssertion = $a;
                },
            ],
        ]));

        $this->registry->handleRegionAssertion('sidebar', $assertion);

        static::assertSame($assertion, $receivedAssertion);
    }

    public function testHandleRegionAssertionThrowsWhenNoHandlerRegisteredForRegionType(): void
    {
        $assertion = new ExpectRegionContains('my-region', 'some text');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No region assertion handler registered for region type "sidebar".');

        $this->registry->handleRegionAssertion('sidebar', $assertion);
    }

    public function testHandleRegionAssertionThrowsWhenHandlerDoesNotSupportAssertionType(): void
    {
        $assertion = new ExpectRegionContains('my-region', 'some text');
        $handler = mock(RegionAssertionHandlerInterface::class, [
            'getHandlers' => [],
        ]);
        $this->registry->registerRegionAssertionHandler('sidebar', $handler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Region assertion handler for region type "sidebar" does not support assertion type "%s".',
                ExpectRegionContains::class
            )
        );

        $this->registry->handleRegionAssertion('sidebar', $assertion);
    }

    public function testRegisterRegionAssertionHandlerOverwritesPreviousHandlerForSameRegionType(): void
    {
        $assertion = new ExpectRegionContains('my-region', 'some text');
        $firstHandlerCalled = false;
        $secondHandlerCalled = false;
        $firstHandler = mock(RegionAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectRegionContains::class => function () use (&$firstHandlerCalled): void {
                    $firstHandlerCalled = true;
                },
            ],
        ]);
        $secondHandler = mock(RegionAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectRegionContains::class => function () use (&$secondHandlerCalled): void {
                    $secondHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerRegionAssertionHandler('sidebar', $firstHandler);

        $this->registry->registerRegionAssertionHandler('sidebar', $secondHandler);
        $this->registry->handleRegionAssertion('sidebar', $assertion);

        static::assertFalse($firstHandlerCalled);
        static::assertTrue($secondHandlerCalled);
    }

    public function testHandleRegionAssertionSupportsMultipleRegionTypes(): void
    {
        $assertion = new ExpectRegionContains('my-region', 'some text');
        $sidebarHandlerCalled = false;
        $headerHandlerCalled = false;
        $sidebarHandler = mock(RegionAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectRegionContains::class => function () use (&$sidebarHandlerCalled): void {
                    $sidebarHandlerCalled = true;
                },
            ],
        ]);
        $headerHandler = mock(RegionAssertionHandlerInterface::class, [
            'getHandlers' => [
                ExpectRegionDoesNotContain::class => function () use (&$headerHandlerCalled): void {
                    $headerHandlerCalled = true;
                },
            ],
        ]);
        $this->registry->registerRegionAssertionHandler('sidebar', $sidebarHandler);
        $this->registry->registerRegionAssertionHandler('header', $headerHandler);

        $this->registry->handleRegionAssertion('sidebar', $assertion);

        static::assertTrue($sidebarHandlerCalled);
        static::assertFalse($headerHandlerCalled);
    }
}
