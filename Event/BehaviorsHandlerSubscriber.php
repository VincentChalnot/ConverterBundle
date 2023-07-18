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

use Sidus\ConverterBundle\Model\Event\BehaviorEvent;
use Sidus\ConverterBundle\Model\Event\ConverterEvent;
use Sidus\ConverterBundle\Model\Behavior\BehaviorConfiguration;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BehaviorsHandlerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 1200],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        foreach ($event->getConfiguration()->getBehaviors() as $behavior) {
            $this->handleBehavior($event, $behavior);
        }
    }

    protected function handleBehavior(ConverterEvent $event, BehaviorConfiguration $behavior): void
    {
        $behaviorEvent = BehaviorEvent::withParent($event, $behavior);
        $this->eventDispatcher->dispatch($behaviorEvent);
        foreach ($behaviorEvent->getProperties() as $property => $value) {
            $event->setProperty($property, $value);
        }
    }
}
