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

namespace Tappet\Tests\Unit\Suite;

use Tappet\Core\Exception\InvalidConfigurationException;
use Tappet\Core\Exception\MissingConfigurationException;
use Tappet\Suite\SuiteInterface;
use Tappet\Suite\SuiteResolver;
use Tappet\Tests\AbstractTestCase;
use Tappet\Tests\Functional\Fixtures\TestSuiteTypeSuite;

/**
 * Class SuiteResolverTest.
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class SuiteResolverTest extends AbstractTestCase
{
    private string $fixturesPath;
    /** @var SuiteResolver<SuiteInterface> */
    private SuiteResolver $suiteResolver;

    public function setUp(): void
    {
        parent::setUp();

        $this->fixturesPath = __DIR__ . '/../../Functional/Fixtures/TappetConfiguredApp';

        $this->suiteResolver = new SuiteResolver(SuiteInterface::class, [$this->fixturesPath]);
    }

    public function testResolveSuiteReturnsSuiteWhenFileExists(): void
    {
        $suite = $this->suiteResolver->resolveSuite('mysuite');

        static::assertInstanceOf(TestSuiteTypeSuite::class, $suite);
    }

    public function testResolveSuiteThrowsWhenFileDoesNotExist(): void
    {
        $this->expectException(MissingConfigurationException::class);
        $this->expectExceptionMessage(
            'Tappet suite config file tappet.nonexistent.suite.php is required but was not found in any of the configured paths: [' . $this->fixturesPath . ']'
        );

        $this->suiteResolver->resolveSuite('nonexistent');
    }

    public function testResolveSuiteThrowsWhenReturnIsNotSuiteInterface(): void
    {
        $fixtureDir = dirname(__DIR__) . '/Fixtures/SuiteResolver';
        $suiteResolver = new SuiteResolver(SuiteInterface::class, [$fixtureDir]);

        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage(sprintf(
            'Return value of module %s is expected to be an instance of %s but was not',
            $fixtureDir . '/tappet.invalid.suite.php',
            SuiteInterface::class
        ));

        $suiteResolver->resolveSuite('invalid');
    }
}
