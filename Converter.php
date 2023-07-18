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

namespace Sidus\ConverterBundle;

use Sidus\ConverterBundle\Model\Event\EventInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Sidus\ConverterBundle\Model\Event\ConverterEvent;
use Sidus\ConverterBundle\Model\ConverterConfiguration;
use Sidus\ConverterBundle\Model\Mapping\Mapping;
use Sidus\ConverterBundle\Registry\ConverterConfigurationRegistry;

/**
 * Entrypoint to convert items.
 */
class Converter implements ConverterInterface
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected ConverterConfigurationRegistry $converterConfigurationRegistry,
    ) {
    }

    public function convert(
        mixed $input,
        ConverterConfiguration|string $configuration,
        mixed $output = null,
    ): mixed {
        if (!$configuration instanceof ConverterConfiguration) {
            $configuration = $this->converterConfigurationRegistry->getConverterConfiguration($configuration);
        }

        $event = new ConverterEvent($input, $configuration, $output);
        $this->eventDispatcher->dispatch($event);

        return $event->getOutput();
    }

    public function convertWithParent(
        EventInterface $parentEvent,
        Mapping $parentMapping,
        mixed $input,
        ConverterConfiguration|string $configuration,
        mixed $output = null,
    ): mixed {
        if (!$configuration instanceof ConverterConfiguration) {
            $configuration = $this->converterConfigurationRegistry->getConverterConfiguration($configuration);
        }

        $event = ConverterEvent::withParent($parentEvent, $parentMapping, $input, $configuration, $output);
        $this->eventDispatcher->dispatch($event);

        return $event->getOutput();
    }
}
