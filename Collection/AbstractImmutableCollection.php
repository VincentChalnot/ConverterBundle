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

namespace Sidus\ConverterBundle\Collection;


/**
 * Immutable collection of items.
 */
abstract class AbstractImmutableCollection implements CollectionInterface
{
    protected \ArrayIterator $collection;

    public function __construct(array $collection)
    {
        $this->collection = new \ArrayIterator($collection);
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->collection->offsetExists($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \LogicException('Trying to set element from immutable collection');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \LogicException('Trying to unset element from immutable collection');
    }

    public function next(): void
    {
        $this->collection->next();
    }

    public function key(): string | float | int | bool | null
    {
        return $this->collection->key();
    }

    public function valid(): bool
    {
        return $this->collection->valid();
    }

    public function rewind(): void
    {
        $this->collection->rewind();
    }

    public function __serialize(): array
    {
        return $this->collection->__serialize();
    }

    public function __unserialize($data): void
    {
        $this->collection->__unserialize($data);
    }

    public function count(): int
    {
        return $this->collection->count();
    }

    public function seek($offset): void
    {
        $this->collection->seek($offset);
    }
}
