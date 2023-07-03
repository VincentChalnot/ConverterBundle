<?php
declare(strict_types=1);

namespace Sidus\ConverterBundle\Helper;

use Sidus\ConverterBundle\Event\EventInterface;
use Sidus\ConverterBundle\Model\ConfigurationInterface;
use Sidus\ConverterBundle\Model\Mapping\Mapping;
use Symfony\Component\PropertyAccess\Exception\AccessException;

class MappingExtractorHelper
{
    public function applyMapping(
        EventInterface $event,
        ConfigurationInterface $config,
        Mapping $mapping,
    ): void {
        $input = $event->getInput();
        if (null === $input) {
            throw new \LogicException("Input cannot be null");
        }
        $outputProperty = $mapping->getOutputProperty();
        if ($event->hasProperty($outputProperty)) {
            throw new \LogicException("Output already contains property {$outputProperty}");
        }
        $inputProperty = $mapping->getInputProperty();
        if (null === $inputProperty) {
            $inputProperty = $outputProperty;
        }
        if ($mapping->isIgnored()) {
            return;
        }
        if ('.' === $inputProperty) {
            $event->setProperty($outputProperty, $input);

            return;
        }
        if ($config->getAccessor()->isReadable($input, $inputProperty)) {
            $event->setProperty($outputProperty, $config->getAccessor()->getValue($input, $inputProperty));

            return;
        }
        if ($mapping->isIgnoreMissing() ?? $config->isIgnoreAllMissing()) {
            return;
        }

        throw new AccessException("Unreadable property '{$inputProperty}'");
    }
}
