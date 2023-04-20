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
 * Check remaining extracted properties and throw an exception if not empty.
 */
class CheckMissingPropertiesSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', -10000],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        if (empty($event->getProperties())) {
            return;
        }

        $m = "Unable to set the following properties on object of type {$event->getConfiguration()->getOutputType()}: ";
        $m .= implode(', ', array_keys($event->getProperties()));
        throw new \RuntimeException($m);
    }
}
