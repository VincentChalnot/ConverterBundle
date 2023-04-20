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

namespace Sidus\ConverterBundle\Model\Mapping;

/**
 * Used to map a value from a property to another property.
 */
class Mapping
{
    public function __construct(
        protected string $outputProperty,
        protected ?string $inputProperty = null,
        protected ?TransformerConfigurationCollection $transformerConfigurations = null,
        protected ?bool $ignoreMissing = null,
    ) {
    }

    public function getInputProperty(): ?string
    {
        return $this->inputProperty;
    }

    public function getOutputProperty(): string
    {
        return $this->outputProperty;
    }

    public function getTransformerConfigurations(): ?TransformerConfigurationCollection
    {
        return $this->transformerConfigurations;
    }

    public function isIgnoreMissing(): ?bool
    {
        return $this->ignoreMissing;
    }
}
