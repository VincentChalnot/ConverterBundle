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

interface ConfigurationInterface
{
    public function getMapping(): MappingCollection;

    public function getAccessor(): PropertyAccessorInterface;

    public function isIgnoreAllMissing(): bool;
}
