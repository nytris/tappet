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

namespace Tappet\Tests\Unit\Core\Standard\Arrangement;

use Mockery\MockInterface;
use Tappet\Core\Environment\EnvironmentInterface;
use Tappet\Core\Fixture\FixtureInterface;
use Tappet\Core\Fixture\ModelInterface;
use Tappet\Core\Standard\Arrangement\LoadMultipleFixtures;
use Tappet\Tests\AbstractTestCase;

/**
 * Class LoadMultipleFixturesTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class LoadMultipleFixturesTest extends AbstractTestCase
{
    private EnvironmentInterface&MockInterface $environment;
    /**
     * @var FixtureInterface<ModelInterface>&MockInterface
     */
    private FixtureInterface&MockInterface $fixture1;
    /**
     * @var FixtureInterface<ModelInterface>&MockInterface
     */
    private FixtureInterface&MockInterface $fixture2;
    private LoadMultipleFixtures $arrangement;

    public function setUp(): void
    {
        parent::setUp();

        $this->environment = mock(EnvironmentInterface::class);
        $this->fixture1 = mock(FixtureInterface::class);
        $this->fixture2 = mock(FixtureInterface::class);

        $this->arrangement = new LoadMultipleFixtures([
            'myFirstHandle' => $this->fixture1,
            'mySecondHandle' => $this->fixture2,
        ]);
    }

    public function testGetFixturesReturnsFixtures(): void
    {
        static::assertSame(
            ['myFirstHandle' => $this->fixture1, 'mySecondHandle' => $this->fixture2],
            $this->arrangement->getFixtures()
        );
    }

    public function testPerformCallsEnvironmentLoadMultipleFixturesWithFixtures(): void
    {
        $this->environment->expects()
            ->loadMultipleFixtures([
                'myFirstHandle' => $this->fixture1,
                'mySecondHandle' => $this->fixture2,
            ])
            ->once();

        $this->arrangement->perform($this->environment);
    }
}
