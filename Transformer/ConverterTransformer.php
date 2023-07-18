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

namespace Sidus\ConverterBundle\Transformer;

use CleverAge\ProcessBundle\Transformer\ConfigurableTransformerInterface;
use Sidus\ConverterBundle\Configuration\ConfigurationBuilder;
use Sidus\ConverterBundle\ConverterInterface;
use Sidus\ConverterBundle\DependencyInjection\Configuration;
use Sidus\ConverterBundle\Model\Event\EventInterface;
use Sidus\ConverterBundle\Model\ConverterConfiguration;
use Sidus\ConverterBundle\Model\Mapping\Mapping;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Enables the usage of the converter as a transformer.
 */
class ConverterTransformer implements ConfigurableTransformerInterface
{
    protected Configuration $converterConfiguration;

    public function __construct(
        protected ConverterInterface $converter,
        protected ConfigurationBuilder $configurationBuilder,
    ) {
        $this->converterConfiguration = new Configuration();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['configuration']);
        $resolver->setAllowedTypes('configuration', ['string', 'array', ConverterConfiguration::class]);
        $resolver->setNormalizer(
            'configuration',
            function (Options $options, string|array|ConverterConfiguration $value) {
                if (is_string($value) || $value instanceof ConverterConfiguration) {
                    return $value;
                }

                // Validates and normalize configuration
                $value = $this->converterConfiguration->buildNestedConfiguration($value);

                return $this->configurationBuilder->resolveConverterConfiguration(uniqid('internal_'), $value);
            }
        );
    }

    public function transform(mixed $value, array $options = []): mixed
    {
        return $this->converter->convert($value, $options['configuration']);
    }

    public function transformWithParent(
        EventInterface $parentEvent,
        Mapping $parentMapping,
        mixed $value,
        array $options = []
    ): mixed {
        return $this->converter->convertWithParent($parentEvent, $parentMapping, $value, $options['configuration']);
    }

    public function getCode(): string
    {
        return 'converter';
    }
}
