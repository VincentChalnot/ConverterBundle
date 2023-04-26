<?php
/*
 * This file is part of the Sidus/ConverterBundle package.
 *
 * Copyright (c) 2021-2023 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sidus\ConverterBundle\Registry;

use Sidus\ConverterBundle\Configuration\ConfigurationBuilder;
use Sidus\ConverterBundle\Model\Behavior\BehaviorConfiguration;
use Sidus\ConverterBundle\Model\ConverterConfiguration;

/**
 * Registry for saved configurations.
 */
class ConverterConfigurationRegistry
{
    /** @var ConverterConfiguration[] */
    protected array $converterConfigurations = [];

    /** @var BehaviorConfiguration[] */
    protected array $behaviorConfigurations = [];

    public function __construct(
        protected ConfigurationBuilder $configurationBuilder,
    ) {
    }

    public function getConverterConfiguration(string $code): ConverterConfiguration
    {
        if (!array_key_exists($code, $this->converterConfigurations)) {
            $this->converterConfigurations[$code] = $this->configurationBuilder->getConverterConfiguration($code);
        }

        return $this->converterConfigurations[$code];
    }

    public function hasConverterConfiguration(string $code): bool
    {
        return array_key_exists($code, $this->converterConfigurations)
            || $this->configurationBuilder->hasConverterConfiguration($code);
    }

    public function addConverterConfiguration(string $code, ConverterConfiguration $converterConfiguration): void
    {
        $this->converterConfigurations[$code] = $converterConfiguration;
    }

    public function getBehaviorConfiguration(string $code): BehaviorConfiguration
    {
        if (!array_key_exists($code, $this->behaviorConfigurations)) {
            $this->behaviorConfigurations[$code] = $this->configurationBuilder->getBehaviorConfiguration($code);
        }

        return $this->behaviorConfigurations[$code];
    }

    public function hasBehaviorConfiguration(string $code): bool
    {
        return array_key_exists($code, $this->behaviorConfigurations)
            || $this->configurationBuilder->hasBehaviorConfiguration($code);
    }

    public function addBehaviorConfiguration(string $code, BehaviorConfiguration $behaviorConfiguration): void
    {
        $this->behaviorConfigurations[$code] = $behaviorConfiguration;
    }
}
