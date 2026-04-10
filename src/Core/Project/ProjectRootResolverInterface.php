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

/**
 * Interface ProjectRootResolverInterface.
 *
 * Resolves the project root directory.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface ProjectRootResolverInterface
{
    /**
     * Resolves the project root directory.
     *
     * @return string The project root directory path.
     */
    public function resolveProjectRoot(): string;
}
