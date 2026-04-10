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

namespace Tappet\Tests\Unit\Cli\Io;

use Tappet\Cli\Io\Output;
use Tappet\Tests\AbstractTestCase;

/**
 * Class OutputTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class OutputTest extends AbstractTestCase
{
    /** @var resource */
    private $stream;
    private Output $output;

    public function setUp(): void
    {
        parent::setUp();

        $this->stream = fopen('php://memory', 'rb+');

        $this->output = new Output($this->stream);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        fclose($this->stream);
    }

    public function testWriteWritesMessageToStream(): void
    {
        $this->output->write('Hello, world!');

        rewind($this->stream);
        static::assertSame('Hello, world!', stream_get_contents($this->stream));
    }

    public function testWriteAppendsSubsequentMessagesToStream(): void
    {
        $this->output->write('First.');
        $this->output->write(' Second.');

        rewind($this->stream);
        static::assertSame('First. Second.', stream_get_contents($this->stream));
    }
}
