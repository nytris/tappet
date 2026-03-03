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

namespace Tappet\Core\Environment;

use Tappet\Core\Environment\Field\FieldInterface;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelProviderInterface;

interface EnvironmentInterface extends ModelProviderInterface
{
    public function assertPage(string $url): void;

    public function getField(string $handle): FieldInterface;

    public function loadFixture(string $handle, FixtureInterface $fixture): void;

    public function visitPage(string $url): void;
}
