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

namespace Tappet\Suite\Plugin;

use Tappet\Common\Event\EventInterface;
use Tappet\Runner\Automation\AutomationInterface;

/**
 * Interface PluginInterface.
 *
 * A plugin that can be added to a test suite to extend its behaviour.
 * Plugins declare event listeners that receive both the fired event and the suite's automation instance.
 *
 * @template TAutomation of AutomationInterface
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface PluginInterface
{
    /**
     * Returns a map of event class FQCNs to listener callables.
     * Each callable receives the event and the automation instance.
     *
     * @return array<class-string<EventInterface>, callable(EventInterface, TAutomation): void>
     */
    public function getListeners(): array;
}
