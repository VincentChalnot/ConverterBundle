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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Creates the output object if null.
 */
class OutputCreatorSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 100],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        if (null !== $event->getOutput()) {
            return;
        }
        $config = $event->getConfiguration();

        if ('array' === $config->getOutputType()) {
            $event->setOutput([]);

            return;
        }

        if (!class_exists($config->getOutputType())) {
            throw new \UnexpectedValueException("Unable to create element of type {$config->getOutputType()}");
        }

        $refl = $event->getOutputReflectionClass();

        $args = [];
        if ($refl->hasMethod('__construct')) {
            $constructor = $refl->getMethod('__construct');
            foreach ($constructor->getParameters() as $parameter) {
                if (!$event->hasProperty($parameter->getName())) {
                    $args[$parameter->getPosition()] = null;
                    continue;
                }
                $args[$parameter->getPosition()] = $event->getProperty($parameter->getName());
                $event->removeProperty($parameter->getName());
            }
        }

        $event->setOutput($refl->newInstanceArgs($args));
    }
}
