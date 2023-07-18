<?php
declare(strict_types=1);

namespace Sidus\ConverterBundle\Event;

use Sidus\ConverterBundle\Model\ConfigurationInterface;

interface EventInterface
{
    public function getInput(): mixed;

    public function getConfiguration(): ConfigurationInterface;

    public function getProperties(): array;

    public function hasProperty(string $property): bool;

    public function getProperty(string $property): mixed;

    public function setProperty(string $property, mixed $input): void;

    public function removeProperty(string $property): void;

    public function getParentEvent(): ?EventInterface;
}
