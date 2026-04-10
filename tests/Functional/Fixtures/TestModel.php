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

namespace Tappet\Tests\Functional\Fixtures;

use Tappet\Core\Fixture\ModelInterface;

/**
 * Class TestModel.
 *
 * Stub model (loaded instance of fixture) for use in functional tests.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestModel implements ModelInterface
{
    public function __construct(public string $serialisedFixture)
    {
    }
}
