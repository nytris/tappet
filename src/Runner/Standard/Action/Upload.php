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

namespace Tappet\Runner\Standard\Action;

use Tappet\Runner\Action\FieldActionInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class Upload.
 *
 * Uploads a file via a file input field.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class Upload implements FieldActionInterface
{
    public function __construct(
        private readonly string $fieldHandle,
        private readonly string $filePath
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getFieldHandle(): string
    {
        return $this->fieldHandle;
    }

    /**
     * Fetches the local path to the file to select for upload.
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->performFieldAction($this);
    }
}
