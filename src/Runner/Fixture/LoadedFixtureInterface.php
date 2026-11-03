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

namespace Tappet\Runner\Fixture;

use Tappet\Common\Fixture\FixtureInterface;
use Tappet\Common\Fixture\LoadedFixtureInterface as CommonLoadedFixtureInterface;
use Tappet\Common\Fixture\ModelInterface;

/**
 * Interface LoadedFixtureInterface.
 *
 * Loaded fixtures are fixtures that have been loaded via the API.
 *
 * @deprecated Use {@see \Tappet\Common\Fixture\LoadedFixtureInterface} instead.
 *
 * @template-covariant TFixture of FixtureInterface<TModel>
 * @template-covariant TModel of ModelInterface
 * @template-extends CommonLoadedFixtureInterface<TFixture, TModel>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface LoadedFixtureInterface extends CommonLoadedFixtureInterface
{
}
