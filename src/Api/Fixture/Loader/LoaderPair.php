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
 * Class LoaderPair.
 *
 * @template TFixture of FixtureInterface<TModel>
 * @template TModel of ModelInterface
 * @template-implements LoaderPairInterface<TFixture, TModel>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class LoaderPair implements LoaderPairInterface
{
    /**
     * @var callable (TFixture): TModel
     */
    private $loader;
    /**
     * @var callable (TFixture, TModel): void
     */
    private $unloader;

    /**
     * @param callable (TFixture): TModel $loader
     * @param callable (TFixture, TModel): void $unloader
     */
    public function __construct(
        callable $loader,
        callable $unloader
    ) {
        $this->loader = $loader;
        $this->unloader = $unloader;
    }

    /**
     * @return callable(TFixture): TModel
     */
    public function getLoader(): callable
    {
        return $this->loader;
    }

    /**
     * @return callable(TFixture, TModel): void
     */
    public function getUnloader(): callable
    {
        return $this->unloader;
    }
}
