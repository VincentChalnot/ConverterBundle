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
use Sidus\ConverterBundle\Helper\MappingExtractorHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Extract properties from input element.
 */
class PropertiesExtractorSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MappingExtractorHelper $mappingExtractorHelper,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ConverterEvent::class => ['convert', 1000],
        ];
    }

    public function convert(ConverterEvent $event): void
    {
        $config = $event->getConfiguration();
        $input = $event->getInput();
        foreach ($config->getMapping() as $mapping) {
            $this->mappingExtractorHelper->applyMapping($event, $config, $mapping, $input);
        }
    }
}
