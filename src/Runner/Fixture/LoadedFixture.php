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
use Tappet\Common\Fixture\ModelInterface;

/**
 * Class LoadedFixture.
 *
 * Loaded fixtures are fixtures that have been loaded via the API.
 *
 * @template TFixture of FixtureInterface<TModel>
 * @template TModel of ModelInterface
 * @implements LoadedFixtureInterface<TFixture, TModel>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class LoadedFixture implements LoadedFixtureInterface
{
    /**
     * @param TFixture $fixture
     * @param TModel $model
     */
    public function __construct(
        private readonly FixtureInterface $fixture,
        private readonly ModelInterface $model,
        private readonly string $handle
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getFixture(): FixtureInterface
    {
        return $this->fixture;
    }

    /**
     * @inheritDoc
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @inheritDoc
     */
    public function getModel(): ModelInterface
    {
        return $this->model;
    }

    /**
     * @inheritDoc
     */
    public function getModelClass(): string
    {
        return $this->model::class;
    }
}
