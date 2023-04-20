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
 * Configures how the normalizer should handle data.
 */
class ConverterConfiguration
{
    public function __construct(
        protected string $outputType,
        protected MappingCollection $mapping,
        protected PropertyAccessorInterface $accessor,
        protected bool $skipNull = false,
        protected bool $ignoreAllMissing = false,
        protected bool $hydrateObject = false,
        protected bool $autoMapping = false,
        protected ?string $inputType = null,
    ) {
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

    public function isSkipNull(): bool
    {
        return $this->skipNull;
    }

    public function isIgnoreAllMissing(): bool
    {
        return $this->ignoreAllMissing;
    }

    public function isHydrateObject(): bool
    {
        return $this->hydrateObject;
    }

    public function isAutoMapping(): bool
    {
        return $this->autoMapping;
    }

    public function getInputType(): ?string
    {
        return $this->inputType;
    }
}
