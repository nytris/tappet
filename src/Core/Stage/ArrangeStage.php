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

namespace Tappet\Core\Stage;

use Tappet\Core\Arrangement\ArrangementInterface;
use Tappet\Core\Environment\EnvironmentInterface;

class ArrangeStage extends AbstractStage
{
    /**
     * @var ArrangementInterface[]
     */
    private $arrangements;

    /**
     * @param ArrangementInterface[] $arrangements
     */
    public function __construct(array $arrangements)
    {
        $this->arrangements = $arrangements;
    }

    /**
     * @return ArrangementInterface[]
     */
    public function getArrangements(): array
    {
        return $this->arrangements;
    }

    public function perform(EnvironmentInterface $environment): void
    {
        foreach ($this->getArrangements() as $arrangement) {
            $arrangement->perform($environment);

            // FIXME: Assert that the current URL matches the currently expected one,
            //        which needs to be explicitly given by the OpenPage arrangement or ExpectNewPage assertion.
        }
    }
}
