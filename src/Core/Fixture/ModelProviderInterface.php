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

namespace Tappet\Core\Fixture;

/**
 * Interface ModelProviderInterface.
 */
interface ModelProviderInterface
{
    /**
     * @template TModel of ModelInterface
     * @param class-string<TModel> $modelClass
     * @param string $handle
     *
     * @return TModel
     */
    public function getFixtureModel(string $modelClass, string $handle): ModelInterface;
}
