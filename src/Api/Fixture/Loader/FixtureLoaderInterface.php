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

namespace Tappet\Api\Fixture\Loader;

use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;

/**
 * Interface FixtureLoaderInterface.
 *
 * @template TFixture of FixtureInterface<TModel>
 * @template TModel of ModelInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface FixtureLoaderInterface
{
    /**
     * @return array<class-string<TFixture>, LoaderPairInterface<TFixture, TModel>>
     */
    public function getLoaderPairs(): array;
}
