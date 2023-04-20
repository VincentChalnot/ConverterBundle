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

namespace Sidus\ConverterBundle\Configuration;

use CleverAge\ProcessBundle\Registry\TransformerRegistry;
use Sidus\ConverterBundle\Model\ConverterConfiguration;
use Sidus\ConverterBundle\Model\Mapping\Mapping;
use Sidus\ConverterBundle\Model\Mapping\MappingCollection;
use Sidus\ConverterBundle\Model\Mapping\TransformerConfiguration;
use Sidus\ConverterBundle\Model\Mapping\TransformerConfigurationCollection;
use Symfony\Component\PropertyAccess\PropertyAccessorBuilder;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Build object converter configuration from array configuration.
 */
class ConfigurationBuilder
{
    public function __construct(
        protected TransformerRegistry $transformerRegistry,
    ) {
    }

    public function resolveConverterConfiguration(array $config): ConverterConfiguration
    {
        return new ConverterConfiguration(
            outputType: $config['output_type'],
            mapping: $this->getMapping($config['mapping']),
            accessor: $this->getPropertyAccessor($config['accessor']),
            skipNull: $config['skip_null'],
            ignoreAllMissing: $config['ignore_all_missing'],
            hydrateObject: $config['hydrate_object'],
            autoMapping: $config['auto_mapping'],
            inputType: $config['input_type'],
        );
    }

    protected function getPropertyAccessor(array $config): PropertyAccessorInterface
    {
        $accessorBuilder = new PropertyAccessorBuilder();
        if ($config['exception_on_invalid_index']) {
            $accessorBuilder->enableExceptionOnInvalidIndex();
        } else {
            $accessorBuilder->disableExceptionOnInvalidIndex();
        }
        if ($config['exception_on_invalid_property_path']) {
            $accessorBuilder->enableExceptionOnInvalidPropertyPath();
        } else {
            $accessorBuilder->disableExceptionOnInvalidPropertyPath();
        }
        if ($config['enable_magic_call']) {
            $accessorBuilder->enableMagicCall();
        } else {
            $accessorBuilder->disableMagicCall();
        }
        if ($config['enable_magic_get']) {
            $accessorBuilder->enableMagicGet();
        } else {
            $accessorBuilder->disableMagicGet();
        }
        if ($config['enable_magic_set']) {
            $accessorBuilder->enableMagicSet();
        } else {
            $accessorBuilder->disableMagicSet();
        }

        return $accessorBuilder->getPropertyAccessor();
    }

    protected function getMapping(array $configs): MappingCollection
    {
        $mappings = [];
        foreach ($configs as $outputProperty => $mappingConfig) {
            $mappings[$outputProperty] = new Mapping(
                outputProperty: $outputProperty,
                inputProperty: $mappingConfig['input_property'],
                transformerConfigurations: $this->getTransformers($mappingConfig['transformers']),
                ignoreMissing: $mappingConfig['ignore_missing'],
            );
        }

        return new MappingCollection($mappings);
    }

    protected function getTransformers(mixed $configs): TransformerConfigurationCollection
    {
        $transformers = [];
        foreach ($configs as $config) {
            foreach ($config as $code => $options) {
                $transformers[] = new TransformerConfiguration(
                    transformer: $this->transformerRegistry->getTransformer($code),
                    options: $options ?? [],
                );
            }
        }

        return new TransformerConfigurationCollection($transformers);
    }
}
