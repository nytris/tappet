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

namespace Tappet\Cli\Io;

/**
 * Class RecordingOutput.
 *
 * Output implementation that records all written messages for testing.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RecordingOutput implements OutputInterface
{
    private string $output = '';

    /**
     * @inheritDoc
     */
    public function write(string $message): void
    {
        $this->output .= $message;
    }

    /**
     * Gets all output that has been written.
     *
     * @return string The recorded output.
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}
