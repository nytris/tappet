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

namespace Tappet\Core\Standard\Arrangement;

use Tappet\Core\Arrangement\AbstractArrangement;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Fixture\FixtureInterface;

class LoadFixture extends AbstractArrangement
{
    /**
     * @var FixtureInterface
     */
    private $fixture;
    /**
     * @var string
     */
    private $handle;

    public function __construct(string $handle, FixtureInterface $fixture)
    {
        $this->fixture = $fixture;
        $this->handle = $handle;
    }

    public function getFixture(): FixtureInterface
    {
        return $this->fixture;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        $environment->loadFixture($this->getHandle(), $this->fixture);
    }
}
