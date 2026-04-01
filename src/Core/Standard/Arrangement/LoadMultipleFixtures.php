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
use Tappet\Core\Fixture\ModelInterface;

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
     * @var array<string, FixtureInterface<ModelInterface>>
     */
    private $fixtures;

    /**
     * @param array<string, FixtureInterface<ModelInterface>> $fixtures
     */
    public function __construct(array $fixtures)
    {
        $this->fixtures = $fixtures;
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
