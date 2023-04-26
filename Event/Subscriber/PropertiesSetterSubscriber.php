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
 * Sets all remaining properties on the output object.
 */
class PropertiesSetterSubscriber implements EventSubscriberInterface
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

        foreach ($event->getProperties() as $propertyName => $value) {
            $config->getAccessor()->setValue($output, $propertyName, $value);
            $event->removeProperty($propertyName);
        }

        $event->setOutput($output);
    }
}
