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

namespace Tappet\Runner\Assertion;

use Tappet\Runner\Matcher\MatcherInterface;

/**
 * Interface RemoteStateAssertionInterface.
 *
 * Represents an assertion performed of some state fetched from the backend via a Tappet API endpoint that is either:
 *
 *   (a) Not present on the page itself, but must instead be fetched from the backend -
 *       for example, a value deliberately never surfaced in the UI for security reasons.
 *   (b) Presented on a page, but that page is not directly relevant to the scenario under test,
 *       and so it would be needlessly inefficient to load that page just to check this value.
 *       Additionally, a bug on that display page could then lead to breaking tests
 *       that are not directly related to it, as they are only using it to surface a value from the backend.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
interface RemoteStateAssertionInterface extends AssertionInterface
{
    /**
     * Fetches the matcher used to assert on the value fetched from the backend.
     */
    public function getMatcher(): MatcherInterface;

    /**
     * Fetches the unique handle identifying the remote state to be fetched and asserted on.
     */
    public function getRemoteStateHandle(): string;
}
