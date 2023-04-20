<?php
declare(strict_types=1);

namespace Sidus\ConverterBundle\Helper;

use Sidus\ConverterBundle\Event\ConverterEvent;
use Sidus\ConverterBundle\Model\ConverterConfiguration;
use Sidus\ConverterBundle\Model\Mapping\Mapping;
use Symfony\Component\PropertyAccess\Exception\AccessException;

class MappingExtractorHelper
{
    public function applyMapping(
        ConverterEvent $event,
        ConverterConfiguration $config,
        Mapping $mapping,
        mixed $input,
    ): void {
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
