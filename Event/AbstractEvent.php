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

namespace Sidus\ConverterBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Carries element around event for conversion.
 */
abstract class AbstractEvent extends Event implements EventInterface
{
    protected mixed $input;

    protected ?self $parentEvent = null;

    protected array $properties = [];

    public function getInput(): mixed
    {
        return $this->input;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): void
    {
        $this->properties = $properties;
    }

    public function hasProperty(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    public function getProperty(string $property): mixed
    {
        if (!$this->hasProperty($property)) {
            throw new \InvalidArgumentException("Property {$property} does not exist");
        }

        return $this->properties[$property];
    }

    public function setProperty(string $property, mixed $input): void
    {
        $this->properties[$property] = $input;
    }

    public function removeProperty(string $property): void
    {
        unset($this->properties[$property]);
    }

    public function getParentEvent(): ?EventInterface
    {
        return $this->parentEvent;
    }
}
