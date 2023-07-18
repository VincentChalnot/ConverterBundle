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

namespace Sidus\ConverterBundle\Model\Event;

use Sidus\ConverterBundle\Model\ConverterConfiguration;
use Sidus\ConverterBundle\Model\Mapping\Mapping;

/**
 * Carries element around event for conversion.
 */
class ConverterEvent extends AbstractEvent
{
    protected ?Mapping $parentMapping = null;

    protected \ReflectionClass $inputReflectionClass;

    protected \ReflectionClass $outputReflectionClass;

    public function __construct(
        protected mixed $input,
        protected ConverterConfiguration $configuration,
        protected mixed $output = null,
    ) {
    }

    public static function withParent(
        EventInterface $parentEvent,
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

    public function getConfiguration(): ConverterConfiguration
    {
        return $this->configuration;
    }

    public function getOutput(): mixed
    {
        return $this->output;
    }

    public function setOutput(mixed $output): void
    {
        $this->output = $output;
    }

    public function getParentMapping(): ?Mapping
    {
        return $this->parentMapping;
    }

    public function getOutputReflectionClass(): \ReflectionClass
    {
        if (!isset($this->outputReflectionClass)) {
            $class = $this->getConfiguration()->getOutputType();
            $this->outputReflectionClass = new \ReflectionClass($class);
        }

        return $this->outputReflectionClass;
    }

    public function getInputReflectionClass(): \ReflectionClass
    {
        if (!isset($this->inputReflectionClass)) {
            $class = $this->getConfiguration()->getInputType();
            $this->inputReflectionClass = new \ReflectionClass($class);
        }

        return $this->inputReflectionClass;
    }
}
