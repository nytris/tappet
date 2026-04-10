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

use Tappet\Cli\Bin\RunCommand;
use Tappet\Cli\Bin\TappetBinary;
use Tappet\Cli\Bin\TappetBinaryInterface;
use Tappet\Cli\Config\ConfigInterface;
use Tappet\Cli\Environment\Environment;
use Tappet\Cli\Io\Output;
use Tappet\Suite\SuiteInterface;
use Tappet\Suite\SuiteResolver;

/**
 * Class DefaultImplementation.
 *
 * Default implementation of Tappet, wiring all standard dependencies together.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class DefaultImplementation implements ImplementationInterface
{
    public function __construct(
        private readonly ConfigInterface $config
    ) {
    }

    /**
     * @inheritDoc
     */
    public function createTappetBinary(string $configRoot, string $projectRoot): TappetBinaryInterface
    {
        $stderr = new Output(STDERR);
        $suiteResolver = new SuiteResolver(SuiteInterface::class, [$configRoot]);
        $runCommand = new RunCommand(
            $this->config,
            $suiteResolver,
            new Output(STDOUT),
            $stderr,
            $projectRoot,
            new Environment()
        );

        return new TappetBinary($runCommand, $stderr);
    }
}
