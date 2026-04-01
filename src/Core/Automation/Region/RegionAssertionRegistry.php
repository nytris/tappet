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

namespace Tappet\Core\Automation\Region;

use InvalidArgumentException;
use Tappet\Core\Assertion\RegionAssertionInterface;

/**
 * Class RegionAssertionRegistry.
 *
 * Maps region assertion types to their handlers and dispatches region assertions accordingly.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class RegionAssertionRegistry implements RegionAssertionRegistryInterface
{
    /**
     * @var array<string, RegionAssertionHandlerInterface>
     */
    private $handlers = [];

    /**
     * @inheritDoc
     */
    public function handleRegionAssertion(string $regionType, RegionAssertionInterface $assertion): void
    {
        if (!array_key_exists($regionType, $this->handlers)) {
            throw new InvalidArgumentException(
                sprintf('No region assertion handler registered for region type "%s".', $regionType)
            );
        }

        $assertionHandlers = $this->handlers[$regionType]->getHandlers();
        $assertionClass = $assertion::class;

        if (!array_key_exists($assertionClass, $assertionHandlers)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Region assertion handler for region type "%s" does not support assertion type "%s".',
                    $regionType,
                    $assertionClass
                )
            );
        }

        ($assertionHandlers[$assertionClass])($assertion);
    }

    /**
     * @inheritDoc
     */
    public function registerRegionAssertionHandler(string $regionType, RegionAssertionHandlerInterface $handler): void
    {
        $this->handlers[$regionType] = $handler;
    }
}
