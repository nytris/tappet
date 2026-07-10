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

/**
 * Class LoadMultipleFixtures.
 *
 * Loads multiple fixtures at once efficiently via a single Fixture API call.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class LoadMultipleFixtures extends AbstractArrangement
{
    /**
     * @param array<string, FixtureInterface<ModelInterface>> $fixtures
     */
    public function __construct(
        private readonly array $fixtures
    ) {
    }

    /**
     * Fetches the fixtures to be loaded.
     *
     * @return array<string, FixtureInterface<ModelInterface>>
     */
    public function getFixtures(): array
    {
        return $this->fixtures;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $environment->loadMultipleFixtures($this->fixtures);
    }
}
