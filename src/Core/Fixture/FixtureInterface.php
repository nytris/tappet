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
 * Class FixtureInterface.
 *
 * Fixtures are automatically deleted again via the API after the scenario has run.
 */
interface FixtureInterface
{
    /**
     * @return class-string<ModelInterface>
     */
    public function getModelFqcn(): string;
}
