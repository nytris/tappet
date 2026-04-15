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

namespace Tappet\Suite;

use Tappet\Core\Exception\InvalidConfigurationException;
use Tappet\Core\Exception\MissingConfigurationException;

/**
 * Class SuiteResolver.
 *
 * Resolves the Tappet suite configuration from `tappet.{suite-name}.suite.php`.
 *
 * @template TSuite of SuiteInterface
 * @implements SuiteResolverInterface<TSuite>
 *
 * @author Dan Phillimore <dan@ovms.co>
 */
class SuiteResolver implements SuiteResolverInterface
{
    /**
     * @var string
     */
    private $configFileNameTemplate;
    /**
     * @var string
     */
    private $localConfigFileNameTemplate;
    /**
     * @var array<string>
     */
    private $paths;
    /**
     * @var class-string<TSuite>
     */
    private $suiteClass;

    /**
     * @param class-string<TSuite> $suiteClass
     * @param array<string> $paths
     */
    public function __construct(
        string $suiteClass,
        array $paths,
        string $configFileNameTemplate = 'tappet.{suite-name}.suite.php',
        string $localConfigFileNameTemplate = 'tappet.{suite-name}.suite.local.php'
    ) {
        $this->configFileNameTemplate = $configFileNameTemplate;
        $this->localConfigFileNameTemplate = $localConfigFileNameTemplate;
        $this->paths = $paths;
        $this->suiteClass = $suiteClass;
    }

    /**
     * @inheritDoc
     *
     * @return TSuite
     */
    public function resolveSuite(string $suiteName): SuiteInterface
    {
        $configFileName = str_replace('{suite-name}', $suiteName, $this->configFileNameTemplate);

        // Check the local config file first, falling back to the standard config file.
        $localConfigFileName = str_replace('{suite-name}', $suiteName, $this->localConfigFileNameTemplate);

        foreach ($this->paths as $path) {
            $localConfigPath = $path . DIRECTORY_SEPARATOR . $localConfigFileName;
            $configPath = $path . DIRECTORY_SEPARATOR . $configFileName;

            if (is_file($localConfigPath)) {
                $resolvedPath = $localConfigPath;
            } elseif (is_file($configPath)) {
                $resolvedPath = $configPath;
            } else {
                continue;
            }

            $config = require $resolvedPath;

            if (!($config instanceof $this->suiteClass)) {
                throw new InvalidConfigurationException(
                    sprintf(
                        'Return value of module %s is expected to be an instance of %s but was not',
                        $resolvedPath,
                        $this->suiteClass
                    )
                );
            }

            return $config;
        }

        throw new MissingConfigurationException(
            sprintf(
                'Tappet suite config file %s is required but was not found in any of the configured paths: [%s]',
                $configFileName,
                implode(', ', $this->paths)
            )
        );
    }
}
