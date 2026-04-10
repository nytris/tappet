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

use Tappet\Core\Fixture\AbstractFixture;

/**
 * Class TestFixture.
 *
 * Stub fixture for use in functional tests.
 *
 * @extends AbstractFixture<TestModel>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class TestFixture extends AbstractFixture
{
    public function __construct(public int $value)
    {
    }

    public static function getModelClass(): string
    {
        return TestModel::class;
    }
}
