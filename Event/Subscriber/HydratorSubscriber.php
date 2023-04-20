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

use Psr\Log\LoggerInterface;
use Sidus\ConverterBundle\Event\ConverterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Hydrates objects by using their reflection (does not use any getter/setter).
 */
class HydratorSubscriber implements EventSubscriberInterface
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 200],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        $config = $event->getConfiguration();
        if (!$config->isHydrateObject()) {
            return;
        }

        $reflectionClass = $event->getOutputReflectionClass();
        $properties = $event->getProperties();
        $output = $event->getOutput();
        if (!$output) {
            $output = $reflectionClass->newInstanceWithoutConstructor();
        }

        foreach ($properties as $propertyName => $value) {
            if (!$reflectionClass->hasProperty($propertyName)) {
                continue;
            }
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->getDeclaringClass()->getProperty($propertyName)->setValue($output, $value);
            unset($properties[$propertyName]);
        }

        if (!empty($properties)) {
            $m = "Some properties were not set by hydration in object of type '{$reflectionClass->getName()}': ";
            $m .= implode(', ', array_keys($properties));
            $this->logger->info($m);
        }

        $event->setProperties($properties);
        $event->setOutput($output);
    }
}
