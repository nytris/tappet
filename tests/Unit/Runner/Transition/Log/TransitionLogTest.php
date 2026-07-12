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

namespace Tappet\Tests\Unit\Runner\Transition\Log;

use Tappet\Runner\Exception\UnexpectedTransitionException;
use Tappet\Runner\Transition\Log\TransitionLog;
use Tappet\Runner\Transition\TransitionInterface;
use Tappet\Tests\AbstractTestCase;

/**
 * Class TransitionLogTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TransitionLogTest extends AbstractTestCase
{
    private TransitionLog $log;

    public function setUp(): void
    {
        parent::setUp();

        $this->log = new TransitionLog();
    }

    public function testConsumeTransitionAdvancesCursorWhenTransitionMatchesCurrentEntry(): void
    {
        $transition = mock(TransitionInterface::class);
        $transition->allows('equals')
            ->andReturn(true);

        $this->log->pushTransition($transition);
        $this->log->consumeTransition($transition);

        static::assertSame(1, $this->log->getCursor());
    }

    public function testConsumeTransitionThrowsWhenTransitionDoesNotMatchCurrentEntry(): void
    {
        $loggedTransition = mock(TransitionInterface::class);
        $loggedTransition->allows('getDescription')
            ->andReturn('navigation to "/page-one"');
        $this->log->pushTransition($loggedTransition);
        $expectedTransition = mock(TransitionInterface::class);
        $expectedTransition->allows('equals')
            ->andReturn(false);
        $expectedTransition->allows('getDescription')
            ->andReturn('navigation to "/page-two"');

        $this->expectException(UnexpectedTransitionException::class);
        $this->expectExceptionMessage(
            'Unexpected navigation to "/page-one" transition at cursor 0, expecting navigation to "/page-two".'
        );

        $this->log->consumeTransition($expectedTransition);
    }

    public function testConsumeTransitionThrowsWhenLogIsExhausted(): void
    {
        $transition = mock(TransitionInterface::class);
        $transition->allows('getDescription')
            ->andReturn('navigation to "/page-one"');

        $this->expectException(UnexpectedTransitionException::class);
        $this->expectExceptionMessage(
            'Unexpected navigation to "/page-one" transition at cursor 0 but log is exhausted.'
        );

        $this->log->consumeTransition($transition);
    }

    public function testFormatReturnsEmptyMarkerWhenNoEntries(): void
    {
        static::assertSame('(empty)', $this->log->format());
    }

    public function testFormatMarksCursorWithArrow(): void
    {
        $transition1 = mock(TransitionInterface::class);
        $transition1->allows('getDescription')
            ->andReturn('navigation to "/page-one"');
        $transition2 = mock(TransitionInterface::class);
        $transition2->allows('getDescription')
            ->andReturn('modal "my-modal" opening');

        $this->log->pushTransition($transition1);
        $this->log->pushTransition($transition2);

        static::assertSame(
            "> [0] navigation to \"/page-one\"\n  [1] modal \"my-modal\" opening",
            $this->log->format(),
        );
    }

    public function testFormatShowsCorrectCursorPositionAfterConsumption(): void
    {
        $transition1 = mock(TransitionInterface::class);
        $transition1->allows('getDescription')
            ->andReturn('navigation to "/page-one"');
        $transition2 = mock(TransitionInterface::class);
        $transition2->allows('getDescription')
            ->andReturn('modal "my-modal" opening');
        $this->log->pushTransition($transition1);
        $this->log->pushTransition($transition2);
        $transition1->allows('equals')->andReturn(true);
        $this->log->consumeTransition($transition1);

        static::assertSame(
            "  [0] navigation to \"/page-one\"\n> [1] modal \"my-modal\" opening",
            $this->log->format(),
        );
    }

    public function testGetCountReturnsZeroWhenEmpty(): void
    {
        static::assertSame(0, $this->log->getCount());
    }

    public function testGetCountReturnsNumberOfEntries(): void
    {
        $this->log->pushTransition(mock(TransitionInterface::class));
        $this->log->pushTransition(mock(TransitionInterface::class));

        static::assertSame(2, $this->log->getCount());
    }

    public function testGetCursorStartsAtZero(): void
    {
        static::assertSame(0, $this->log->getCursor());
    }

    public function testGetEntriesReturnsEmptyArrayWhenEmpty(): void
    {
        static::assertSame([], $this->log->getEntries());
    }

    public function testGetEntriesReturnsPushedEntries(): void
    {
        $transition1 = mock(TransitionInterface::class);
        $transition2 = mock(TransitionInterface::class);

        $this->log->pushTransition($transition1);
        $this->log->pushTransition($transition2);

        static::assertSame([$transition1, $transition2], $this->log->getEntries());
    }

    public function testPushTransitionAppendsEntry(): void
    {
        $transition = mock(TransitionInterface::class);

        $this->log->pushTransition($transition);

        static::assertSame([$transition], $this->log->getEntries());
    }

    public function testResetClearsEntries(): void
    {
        $this->log->pushTransition(mock(TransitionInterface::class));
        $this->log->reset();

        static::assertSame([], $this->log->getEntries());
    }

    public function testResetResetsCursor(): void
    {
        $transition = mock(TransitionInterface::class);
        $transition->allows('equals')->andReturn(true);

        $this->log->pushTransition($transition);
        $this->log->consumeTransition($transition);
        $this->log->reset();

        static::assertSame(0, $this->log->getCursor());
    }
}
