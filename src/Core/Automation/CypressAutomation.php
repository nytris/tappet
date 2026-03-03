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

namespace Tappet\Core\Automation;

class CypressAutomation implements AutomationInterface
{
    /**
     * @var mixed
     */
    private $cy;

    public function __construct(mixed $cy)
    {
        $this->cy = $cy;
    }

    public function assertPage(string $url): void
    {
        $this->cy->url()->should('eq', $url);
    }

    public function typeField(string $fieldHandle, string $text): void
    {
        $this->cy->get('[data-tappet-field="' . $fieldHandle . '"]')->type($text);
    }

    public function visitPage(string $url): void
    {
        $this->cy->visit($url);
    }
}
