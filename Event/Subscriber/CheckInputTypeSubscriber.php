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
class CheckInputTypeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 10000],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        $inputType = $event->getConfiguration()->getInputType();
        if (null === $inputType) {
            return;
        }
        if (null === $event->getInput() && $event->getConfiguration()->isSkipNull()) {
            $event->stopPropagation();

            return;
        }
        $type = gettype($event->getInput());
        if ($type === $inputType) {
            return;
        }
        if (!$event->getInput() instanceof $inputType) {
            throw new \RuntimeException(
                message: "Incorrect input type, expecting '{$inputType}', got '{$type}' instead",
            );
        }
    }
}
