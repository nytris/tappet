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

namespace Tappet\Cli\Implementation;

use Tappet\Cli\Bin\TappetBinaryInterface;

/**
 * Interface ImplementationInterface.
 *
 * Defines the contract for Tappet implementations.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ImplementationInterface
{
    /**
     * Creates the TappetBinary instance.
     */
    public function createTappetBinary(string $configRoot, string $projectRoot): TappetBinaryInterface;
}
