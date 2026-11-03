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
use Tappet\Common\Fixture\LoadedFixture as CommonLoadedFixture;
use Tappet\Common\Fixture\ModelInterface;

/**
 * Class LoadedFixture.
 *
 * Loaded fixtures are fixtures that have been loaded via the API.
 *
 * @deprecated Use {@see \Tappet\Common\Fixture\LoadedFixture} instead.
 *
 * @template TFixture of FixtureInterface<TModel>
 * @template TModel of ModelInterface
 * @extends CommonLoadedFixture<TFixture, TModel>
 * @implements LoadedFixtureInterface<TFixture, TModel>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class LoadedFixture extends CommonLoadedFixture implements LoadedFixtureInterface
{
}
