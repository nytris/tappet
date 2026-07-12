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

namespace Tappet\Tests\Unit\Runner\Standard\Matcher;

use Tappet\Runner\Matcher\MatcherInterface;
use Tappet\Runner\Standard\Matcher\ExactText;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ExactTextTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ExactTextTest extends AbstractTestCase
{
    public function testImplementsMatcherInterface(): void
    {
        static::assertInstanceOf(MatcherInterface::class, new ExactText('some text'));
    }

    public function testGetTextReturnsGivenText(): void
    {
        $matcher = new ExactText('some text');

        static::assertSame('some text', $matcher->getText());
    }
}
