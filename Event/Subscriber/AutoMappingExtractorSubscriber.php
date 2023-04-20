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
        private readonly MappingExtractorHelper $mappingExtractorHelper,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 1100],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        $config = $event->getConfiguration();
        if (!$config->isAutoMapping()) {
            return;
        }

        $input = $event->getInput();
        $outputRefl = new \ReflectionClass($config->getOutputType());
        foreach ($outputRefl->getProperties() as $property) {
            if ($config->getMapping()->offsetExists($property->getName())) {
                continue;
            }
            $mapping = new Mapping(
                outputProperty: $property->getName(),
                ignoreMissing: true,
            );
            $this->mappingExtractorHelper->applyMapping($event, $config, $mapping, $input);
        }
    }

}