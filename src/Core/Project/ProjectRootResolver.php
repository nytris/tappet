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

namespace Tappet\Core\Project;

use Composer\Autoload\ClassLoader;
use ReflectionClass;

/**
 * Class ProjectRootResolver.
 *
 * Resolves the project root directory.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ProjectRootResolver implements ProjectRootResolverInterface
{
    /**
     * @var ReflectionClass<ClassLoader>
     */
    private $classLoaderReflectionClass;

    /**
     * @param ReflectionClass<ClassLoader> $classLoaderReflectionClass
     */
    public function __construct(ReflectionClass $classLoaderReflectionClass)
    {
        $this->classLoaderReflectionClass = $classLoaderReflectionClass;
    }

    /**
     * @inheritDoc
     */
    public function resolveProjectRoot(): string
    {
        return dirname($this->classLoaderReflectionClass->getFileName(), 3);
    }
}
