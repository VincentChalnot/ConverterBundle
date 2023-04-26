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

use Sidus\ConverterBundle\Event\BehaviorEvent;
use Sidus\ConverterBundle\Event\ConverterEvent;
use Sidus\ConverterBundle\Event\EventInterface;
use Sidus\ConverterBundle\Helper\MappingExtractorHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Extract properties from input element.
 */
class PropertiesExtractorSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected readonly MappingExtractorHelper $mappingExtractorHelper,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 1000],
            BehaviorEvent::class => ['convert', 1000],
        ];
    }

    public function convert(EventInterface $event): void
    {
        $config = $event->getConfiguration();
        foreach ($config->getMapping() as $mapping) {
            $this->mappingExtractorHelper->applyMapping($event, $config, $mapping);
        }
    }
}
