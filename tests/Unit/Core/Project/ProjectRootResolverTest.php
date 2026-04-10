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

namespace Tappet\Tests\Unit\Core\Project;

use Composer\Autoload\ClassLoader;
use ReflectionClass;
use Tappet\Core\Project\ProjectRootResolver;
use Tappet\Tests\AbstractTestCase;

/**
 * Class ProjectRootResolverTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ProjectRootResolverTest extends AbstractTestCase
{
    private ProjectRootResolver $projectRootResolver;

    public function setUp(): void
    {
        parent::setUp();

        $classLoaderReflection = new ReflectionClass(ClassLoader::class);

        $this->projectRootResolver = new ProjectRootResolver($classLoaderReflection);
    }

    public function testResolveProjectRootReturnsCorrectPath(): void
    {
        $projectRoot = $this->projectRootResolver->resolveProjectRoot();

        static::assertSame(dirname(__DIR__, 4), $projectRoot);
    }
}
