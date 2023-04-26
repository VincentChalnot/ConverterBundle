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

use Sidus\ConverterBundle\ConverterInterface;
use Sidus\ConverterBundle\Event\BehaviorEvent;
use Sidus\ConverterBundle\Event\ConverterEvent;
use Sidus\ConverterBundle\Event\EventInterface;
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
            $propertyName = $mapping->getOutputProperty();
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
