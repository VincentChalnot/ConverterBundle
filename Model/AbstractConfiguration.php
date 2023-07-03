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

namespace Sidus\ConverterBundle\Model;

use Sidus\ConverterBundle\Model\Mapping\MappingCollection;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Generic configuration for behaviors and converters.
 */
class AbstractConfiguration implements ConfigurationInterface
{
    public function __construct(
        protected string $code,
        protected string $inputType,
        protected string $outputType,
        protected MappingCollection $mapping,
        protected PropertyAccessorInterface $accessor,
        protected bool $ignoreAllMissing = false,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getInputType(): string
    {
        return $this->inputType;
    }

    public function getOutputType(): string
    {
        return $this->outputType;
    }

    public function getMapping(): MappingCollection
    {
        return $this->mapping;
    }

    public function getAccessor(): PropertyAccessorInterface
    {
        return $this->accessor;
    }

    public function isIgnoreAllMissing(): bool
    {
        return $this->ignoreAllMissing;
    }
}
