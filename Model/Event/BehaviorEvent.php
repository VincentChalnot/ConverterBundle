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

namespace Sidus\ConverterBundle\Model\Event;


use Sidus\ConverterBundle\Model\Behavior\BehaviorConfiguration;

/**
 * Carries element around event for conversion.
 */
class BehaviorEvent extends AbstractEvent
{
    public function __construct(
        protected mixed $input,
        protected BehaviorConfiguration $configuration,
    ) {
    }

    public static function withParent(
        EventInterface $parentEvent,
        BehaviorConfiguration $configuration,
    ): self {
        $event = new self($parentEvent->getInput(), $configuration);
        $event->parentEvent = $parentEvent;

        return $event;
    }

    public function getConfiguration(): BehaviorConfiguration
    {
        return $this->configuration;
    }
}
