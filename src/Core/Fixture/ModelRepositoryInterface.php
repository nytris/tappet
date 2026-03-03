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
 * Class ModelRepositoryInterface.
 */
interface ModelRepositoryInterface extends ModelProviderInterface
{
    public function loadFixture(string $handle, FixtureInterface $fixture): void;

    public function purge(): void;
}
