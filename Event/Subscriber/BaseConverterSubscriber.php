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
 * Handles basic conversion.
 */
class BaseConverterSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => 'convert',
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        $config = $event->getConfiguration();
        $output = $event->getOutput();

        $properties = $event->getProperties();
        foreach ($properties as $propertyName => $value) {
            $config->getAccessor()->setValue($output, $propertyName, $value);
            unset($properties[$propertyName]);
        }

        $event->setProperties($properties);
        $event->setOutput($output);
    }
}
