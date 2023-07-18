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

namespace Sidus\ConverterBundle\Event\Subscriber;

use Sidus\ConverterBundle\Event\ConverterEvent;
use Sidus\ConverterBundle\Helper\MappingExtractorHelper;
use Sidus\ConverterBundle\Model\Mapping\Mapping;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Automatically extract properties from input element when no mapping is defined.
 */
class AutoMappingExtractorSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected readonly MappingExtractorHelper $mappingExtractorHelper,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 900],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        $config = $event->getConfiguration();
        if (!$config->isAutoMapping()) {
            return;
        }
        if ('array' === $config->getOutputType()) {
            $refl = $event->getInputReflectionClass();
        } else {
            $refl = $event->getOutputReflectionClass();
        }

        $config = $event->getConfiguration();
        foreach ($refl->getProperties() as $property) {
            $outputProperty = $this->getProperty($property, $config->getOutputType());
            if ($event->hasProperty($outputProperty)) {
                continue;
            }
            $inputProperty = $this->getProperty($property, $config->getInputType());
            $mapping = new Mapping(
                outputProperty: $outputProperty,
                inputProperty: $inputProperty,
                ignoreMissing: true,
            );
            $this->mappingExtractorHelper->applyMapping($event, $config, $mapping);
        }
    }

    private function getProperty(\ReflectionProperty $property, string $type): string
    {
        if ('array' === $type) {
            return "[{$property->getName()}]";
        }

        return $property->getName();
    }
}
