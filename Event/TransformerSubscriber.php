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

use Sidus\ConverterBundle\ConverterInterface;
use Sidus\ConverterBundle\Model\Event\BehaviorEvent;
use Sidus\ConverterBundle\Model\Event\ConverterEvent;
use Sidus\ConverterBundle\Model\Event\EventInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Transform properties based on mapping.
 */
class TransformerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected ConverterInterface $converter,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 900],
            BehaviorEvent::class => ['convert', 900],
        ];
    }

    public function convert(EventInterface $event): void
    {
        $config = $event->getConfiguration();

        foreach ($config->getMapping() as $mapping) {
            if ($mapping->isIgnored()) {
                continue;
            }
            $propertyName = $mapping->getOutputProperty();
            if ($mapping->isIgnoreMissing() && !$event->hasProperty($propertyName)) {
                continue;
            }
            $value = $event->getProperty($propertyName);

            // First, transformers
            $transformerConfigs = $mapping->getTransformerConfigurations();
            if (null !== $transformerConfigs) {
                if (!$event->hasProperty($propertyName)) {
                    continue; // Missing property, don't care it's handled by another event
                }
                foreach ($transformerConfigs as $transformerConfig) {
                    $value = $transformerConfig->transform($event, $mapping, $value);
                }
            }

            $event->setProperty($propertyName, $value);
        }
    }
}
