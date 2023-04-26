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

namespace Sidus\ConverterBundle\Model\Behavior;

use Sidus\ConverterBundle\Collection\AbstractImmutableCollection;

/**
 * Collection of BehaviorConfiguration.
 */
class BehaviorConfigurationCollection extends AbstractImmutableCollection
{
    public function current(): BehaviorConfiguration
    {
        return $this->collection->current();
    }

    public function offsetGet(mixed $offset): BehaviorConfiguration
    {
        return $this->collection->offsetGet($offset);
    }
}
