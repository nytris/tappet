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

namespace Tappet\Runner\Transition\Log;

use Tappet\Runner\Exception\UnexpectedTransitionException;
use Tappet\Runner\Transition\TransitionInterface;

/**
 * Class TransitionLog.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TransitionLog implements TransitionLogInterface
{
    private int $cursor = 0;
    /**
     * @var TransitionInterface[]
     */
    private array $entries = [];

    /**
     * @inheritDoc
     */
    public function consumeTransition(TransitionInterface $transition): void
    {
        if ($this->cursor >= count($this->entries)) {
            throw new UnexpectedTransitionException(
                'Unexpected ' . $transition->getDescription() . ' transition at cursor ' . $this->cursor .
                ' but log is exhausted.' .
                "\nLog:\n" . $this->format()
            );
        }

        $loggedTransition = $this->entries[$this->cursor];

        if (!$transition->equals($loggedTransition)) {
            throw new UnexpectedTransitionException(
                sprintf(
                    'Unexpected %s transition at cursor %d' . ", expecting %s.\nLog:\n%s",
                    $loggedTransition->getDescription(),
                    $this->cursor,
                    $transition->getDescription(),
                    $this->format()
                )
            );
        }

        $this->cursor++;
    }

    /**
     * @inheritDoc
     */
    public function format(): string
    {
        if ($this->entries === []) {
            return '(empty)';
        }

        $lines = [];

        foreach ($this->entries as $i => $transition) {
            $prefix = $i === $this->cursor ? '>' : ' ';
            $lines[] = $prefix . ' [' . $i . '] ' . $transition->getDescription();
        }

        return implode("\n", $lines);
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        return count($this->entries);
    }

    /**
     * @inheritDoc
     */
    public function getCursor(): int
    {
        return $this->cursor;
    }

    /**
     * @inheritDoc
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @inheritDoc
     */
    public function pushTransition(TransitionInterface $transition): void
    {
        $this->entries[] = $transition;
    }

    /**
     * @inheritDoc
     */
    public function reset(): void
    {
        $this->entries = [];
        $this->cursor = 0;
    }
}
