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

use Tappet\Cli\Io\RecordingOutput;
use Tappet\Tests\AbstractTestCase;

/**
 * Class RecordingOutputTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RecordingOutputTest extends AbstractTestCase
{
    private RecordingOutput $output;

    public function setUp(): void
    {
        parent::setUp();

        $this->output = new RecordingOutput();
    }

    public function testGetOutputReturnsEmptyStringInitially(): void
    {
        static::assertSame('', $this->output->getOutput());
    }

    public function testWriteAppendsMessageToOutput(): void
    {
        $this->output->write('Hello, world!');

        static::assertSame('Hello, world!', $this->output->getOutput());
    }

    public function testWriteAppendsMultipleMessagesInOrder(): void
    {
        $this->output->write('First.');
        $this->output->write('Second.');

        static::assertSame('First.Second.', $this->output->getOutput());
    }
}
