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

namespace Tappet\Runner\Automation\Matcher;

use InvalidArgumentException;
use Tappet\Runner\Matcher\ContextInterface;
use Tappet\Runner\Matcher\MatcherInterface;

/**
 * Class MatcherRegistry.
 *
 * Maps matcher types to their handlers and dispatches matching accordingly.
 *
 * @template TContext of ContextInterface
 * @template-implements MatcherRegistryInterface<TContext>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class MatcherRegistry implements MatcherRegistryInterface
{
    /**
     * @var array<string, array<class-string<MatcherInterface>, callable(MatcherInterface, TContext): void>>
     */
    private array $handlers = [];

    /**
     * @inheritDoc
     */
    public function handleMatcher(string $matcherType, MatcherInterface $matcher, ContextInterface $context): void
    {
        if (!array_key_exists($matcherType, $this->handlers)) {
            throw new InvalidArgumentException(
                sprintf('No matcher handler registered for matcher type "%s".', $matcherType)
            );
        }

        $matchHandlers = $this->handlers[$matcherType];
        $matcherClass = $matcher::class;

        if (!array_key_exists($matcherClass, $matchHandlers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Matcher handler for matcher type "%s" does not support matcher "%s".',
                    $matcherType,
                    $matcherClass
                )
            );
        }

        ($matchHandlers[$matcherClass])($matcher, $context);
    }

    /**
     * @inheritDoc
     */
    public function registerMatchHandler(string $matcherType, MatchHandlerInterface $handler): void
    {
        $this->handlers[$matcherType] = array_merge($this->handlers[$matcherType] ?? [], $handler->getHandlers());
    }
}
