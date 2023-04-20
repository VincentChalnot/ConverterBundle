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
use Sidus\ConverterBundle\Model\ConverterConfiguration;

/**
 * Registry for saved configurations.
 */
class ConverterConfigurationRegistry
{
    /** @var ConverterConfiguration[] */
    protected array $resolvedConfigurations = [];

    public function __construct(
        protected array $arrayConfigurations,
        protected ConfigurationBuilder $configurationBuilder,
    ) {
    }

    public function getConfiguration(string $code): ConverterConfiguration
    {
        if (!$this->hasConfiguration($code)) {
            throw new \RuntimeException("Missing converter configuration {$code}");
        }
        if (!array_key_exists($code, $this->resolvedConfigurations)) {
            $this->resolvedConfigurations[$code] = $this->configurationBuilder->resolveConverterConfiguration(
                $this->arrayConfigurations[$code]
            );
        }

        return $this->resolvedConfigurations[$code];
    }

    public function hasConfiguration(string $code): bool
    {
        return array_key_exists($code, $this->resolvedConfigurations)
            || array_key_exists($code, $this->arrayConfigurations);
    }
}
