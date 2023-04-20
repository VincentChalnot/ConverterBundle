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

use Sidus\ConverterBundle\Collection\AbstractImmutableCollection;

/**
 * Collection of MappingItems.
 */
class MappingCollection extends AbstractImmutableCollection
{
    public function current(): Mapping
    {
        return $this->collection->current();
    }

    public function offsetGet(mixed $offset): Mapping
    {
        return $this->collection->offsetGet($offset);
    }
}
