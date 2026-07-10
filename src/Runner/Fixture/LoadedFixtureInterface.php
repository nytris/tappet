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
 * Interface LoadedFixtureInterface.
 *
 * Loaded fixtures are fixtures that have been loaded via the API.
 *
 * @template-covariant TFixture of FixtureInterface<TModel>
 * @template-covariant TModel of ModelInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface LoadedFixtureInterface
{
    /**
     * Fetches the fixture loaded via the API.
     *
     * @return TFixture
     */
    public function getFixture(): FixtureInterface;

    /**
     * Fetches the handle of the fixture loaded via the API.
     */
    public function getHandle(): string;

    /**
     * Fetches the model for the loaded fixture.
     *
     * @return ModelInterface
     */
    public function getModel(): ModelInterface;

    /**
     * Fetches the class name of the model for the loaded fixture.
     *
     * @return class-string<TModel>
     */
    public function getModelClass(): string;
}
