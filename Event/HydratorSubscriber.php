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

use Psr\Log\LoggerInterface;
use Sidus\ConverterBundle\Model\Event\ConverterEvent;
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
        $output = $event->getOutput();
        if (!$output) {
            $output = $reflectionClass->newInstanceWithoutConstructor();
        }

        foreach ($event->getProperties() as $propertyName => $value) {
            if (!$reflectionClass->hasProperty($propertyName)) {
                continue;
            }
            $reflectionProperty = $reflectionClass->getProperty($propertyName);
            $reflectionProperty->getDeclaringClass()->getProperty($propertyName)->setValue($output, $value);
            $event->removeProperty($propertyName);
        }

        if (!empty($event->getProperties())) {
            $m = "Some properties were not set by hydration in object of type '{$reflectionClass->getName()}': ";
            $m .= implode(', ', array_keys($event->getProperties()));
            $this->logger->info($m);
        }

        $event->setOutput($output);
    }
}
