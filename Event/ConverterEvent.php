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

use Sidus\ConverterBundle\Model\ConverterConfiguration;
use Sidus\ConverterBundle\Model\Mapping\Mapping;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Carries element around event for conversion.
 */
class ConverterEvent extends Event
{
    protected ?self $parentEvent = null;

    protected ?Mapping $parentMapping = null;

    protected array $properties = [];

    protected ?\ReflectionClass $outputReflectionClass = null;

    public function __construct(
        protected mixed $input,
        protected ConverterConfiguration $configuration,
        protected mixed $output = null,
    ) {
    }

    public static function withParent(
        self $parentEvent,
        Mapping $parentMapping,
        mixed $input,
        ConverterConfiguration $configuration,
        mixed $output = null,
    ): self {
        $event = new self($input, $configuration, $output);
        $event->parentEvent = $parentEvent;
        $event->parentMapping = $parentMapping;

        return $event;
    }

    public function getInput(): mixed
    {
        return $this->input;
    }

    public function getOutput(): mixed
    {
        return $this->output;
    }

    public function setOutput(mixed $output): void
    {
        $this->output = $output;
    }

    public function getConfiguration(): ConverterConfiguration
    {
        return $this->configuration;
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
        return isset($this->properties[$property]);
    }

    public function setProperty(string $property, mixed $input): void
    {
        $this->properties[$property] = $input;
    }

    public function getOutputReflectionClass(): \ReflectionClass
    {
        if (!$this->outputReflectionClass) {
            $class = $this->getConfiguration()->getOutputType();
            $this->outputReflectionClass = new \ReflectionClass($class);
        }

        return $this->outputReflectionClass;
    }

    public function getParentEvent(): ?ConverterEvent
    {
        return $this->parentEvent;
    }

    public function getParentMapping(): ?Mapping
    {
        return $this->parentMapping;
    }
}
