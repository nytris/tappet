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

namespace Tappet\Runner\Standard\Matcher;

use Tappet\Runner\Matcher\MatcherInterface;

/**
 * Class ExactText.
 *
 * Matches a cell/item whose text contents are exactly equal to the given text.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExactText implements MatcherInterface
{
    public function __construct(
        private readonly string $text
    ) {
    }

    /**
     * Fetches the text that the matched cell/item's contents must equal exactly.
     */
    public function getText(): string
    {
        return $this->text;
    }
}
