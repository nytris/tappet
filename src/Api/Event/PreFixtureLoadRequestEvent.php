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

namespace Tappet\Api\Event;

use Tappet\Common\Event\EventInterface;
use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\ModelInterface;

/**
 * Class PreFixtureLoadRequestEvent.
 *
 * Raised during a fixture load API call, before any fixtures are loaded.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class PreFixtureLoadRequestEvent implements EventInterface
{
    /**
     * @param array<class-string<FixtureInterface<ModelInterface>>, array<string, FixtureInterface<ModelInterface>>> $fixturesByClassAndHandle
     */
    public function __construct(
        private readonly array $fixturesByClassAndHandle
    ) {
    }

    /**
     * Fetches the fixtures to be loaded of the given fixture class, keyed by handle.
     *
     * @template TFixture of FixtureInterface<ModelInterface>
     *
     * @param class-string<TFixture> $fixtureClass
     * @return array<string, TFixture>
     */
    public function getFixturesForClass(string $fixtureClass): array
    {
        return $this->fixturesByClassAndHandle[$fixtureClass] ?? [];
    }

    /**
     * Fetches all loaded fixtures, keyed by class and handle.
     *
     * @return array<class-string<FixtureInterface<ModelInterface>>, array<string, FixtureInterface<ModelInterface>>>
     */
    public function getFixtures(): array
    {
        return $this->fixturesByClassAndHandle;
    }
}
