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

namespace Tappet\Tests\Unit\Cli\Bin;

use Tappet\Cli\Bin\CliParser;
use Tappet\Tests\AbstractTestCase;

/**
 * Class CliParserTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class CliParserTest extends AbstractTestCase
{
    private CliParser $parser;

    public function setUp(): void
    {
        parent::setUp();

        $this->parser = new CliParser();
    }

    public function testParseWithOnlyScriptNameReturnsNoCommandNoOptionsNoPositionalArgs(): void
    {
        $result = $this->parser->parse(['tappet']);

        static::assertSame('tappet', $result->getScriptName());
        static::assertNull($result->getCommand());
        static::assertSame([], $result->getOptions());
        static::assertNull($result->getPositionalArg(0));
    }

    public function testParseExtractsCommand(): void
    {
        $result = $this->parser->parse(['tappet', 'run']);

        static::assertSame('run', $result->getCommand());
    }

    public function testParseExtractsPositionalArgAfterCommand(): void
    {
        $result = $this->parser->parse(['tappet', 'run', 'my-suite']);

        static::assertSame('my-suite', $result->getPositionalArg(0));
    }

    public function testParseExtractsMultiplePositionalArgsAfterCommand(): void
    {
        $result = $this->parser->parse(['tappet', 'run', 'first', 'second']);

        static::assertSame('first', $result->getPositionalArg(0));
        static::assertSame('second', $result->getPositionalArg(1));
    }

    public function testParseReturnsNullForAbsentPositionalArg(): void
    {
        $result = $this->parser->parse(['tappet', 'run']);

        static::assertNull($result->getPositionalArg(0));
    }

    public function testParseExtractsOptionInSpaceFormat(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--my-option', 'my-value']);

        static::assertSame('my-value', $result->getOption('my-option'));
    }

    public function testParseExtractsOptionInEqualsFormat(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--my-option=my-value']);

        static::assertSame('my-value', $result->getOption('my-option'));
    }

    public function testParseExtractsOptionInEqualsFormatWithEqualsNestedInsideValue(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--my-option=my-key=my-value']);

        static::assertSame('my-key=my-value', $result->getOption('my-option'));
    }

    public function testParseExtractsMultipleOptionsInSpaceFormat(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--opt-a', 'one', '--opt-b', 'two']);

        static::assertSame('one', $result->getOption('opt-a'));
        static::assertSame('two', $result->getOption('opt-b'));
    }

    public function testParseExtractsMultipleOptionsInEqualsFormat(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--opt-a=one', '--opt-b=two']);

        static::assertSame('one', $result->getOption('opt-a'));
        static::assertSame('two', $result->getOption('opt-b'));
    }

    public function testParseExtractsMultipleOptionsInEqualsFormatWithEqualsNestedInsideValues(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--opt-a=one=two', '--opt-b=three=four']);

        static::assertSame('one=two', $result->getOption('opt-a'));
        static::assertSame('three=four', $result->getOption('opt-b'));
    }

    public function testParseWithMixedSpaceAndEqualsFormats(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--opt-a', 'one', '--opt-b=two']);

        static::assertSame('one', $result->getOption('opt-a'));
        static::assertSame('two', $result->getOption('opt-b'));
    }

    public function testParseTreatsOptionWithNoFollowingValueAsBooleanTrue(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--my-flag']);

        static::assertTrue($result->getOption('my-flag'));
    }

    public function testParseTreatsOptionFollowedByAnotherOptionAsBooleanTrue(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--flag-a', '--option-b', 'value']);

        static::assertTrue($result->getOption('flag-a'));
        static::assertSame('value', $result->getOption('option-b'));
    }

    public function testParseReturnsNullForAbsentOption(): void
    {
        $result = $this->parser->parse(['tappet', 'run']);

        static::assertNull($result->getOption('absent'));
    }

    public function testParseExtractsSpaceFormatOptionBeforeCommand(): void
    {
        $result = $this->parser->parse(['tappet', '--project', '/some/path', 'run']);

        static::assertSame('/some/path', $result->getOption('project'));
        static::assertSame('run', $result->getCommand());
    }

    public function testParseExtractsEqualsFormatOptionBeforeCommand(): void
    {
        $result = $this->parser->parse(['tappet', '--project=/some/path', 'run']);

        static::assertSame('/some/path', $result->getOption('project'));
        static::assertSame('run', $result->getCommand());
    }

    public function testGetOptionsReturnsAllParsedOptions(): void
    {
        $result = $this->parser->parse(['tappet', 'run', '--opt-a', 'one', '--opt-b=two']);

        static::assertSame(['opt-a' => 'one', 'opt-b' => 'two'], $result->getOptions());
    }
}
