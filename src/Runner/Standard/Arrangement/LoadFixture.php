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

namespace Tappet\Runner\Standard\Arrangement;

use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;
use Tappet\Runner\Arrangement\AbstractArrangement;
use Tappet\Runner\Environment\EnvironmentInterface;

class LoadFixture extends AbstractArrangement
{
    /**
     * @param FixtureInterface<ModelInterface> $fixture
     */
    public function __construct(
        private readonly string $handle,
        private readonly FixtureInterface $fixture
    ) {
    }

    /**
     * @return FixtureInterface<ModelInterface>
     */
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
