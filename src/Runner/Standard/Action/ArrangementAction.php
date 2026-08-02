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

namespace Tappet\Runner\Standard\Action;

use Tappet\Runner\Action\ActionInterface;
use Tappet\Runner\Arrangement\ArrangementInterface;
use Tappet\Runner\Environment\EnvironmentInterface;

/**
 * Class ArrangementAction.
 *
 * Performs an arrangement during the act stage of a scenario.
 *
 * Arrangements are normally only performed during the arrangement stage, but wrapping one
 * explicitly in an ArrangementAction makes it obvious when this is being done mid-act,
 * for example to load an additional fixture partway through a scenario.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class ArrangementAction implements ActionInterface
{
    public function __construct(
        private readonly ArrangementInterface $arrangement
    ) {
    }

    /**
     * Fetches the arrangement that will be performed.
     */
    public function getArrangement(): ArrangementInterface
    {
        return $this->arrangement;
    }

    /**
     * @inheritDoc
     */
    public function perform(EnvironmentInterface $environment): void
    {
        $this->arrangement->perform($environment);
    }
}
