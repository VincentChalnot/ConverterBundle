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

use CleverAge\ProcessBundle\Exception\TransformerException;
use CleverAge\ProcessBundle\Transformer\ConfigurableTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Shorthand transformer to call the converter for each element of an array.
 */
class ArrayMapConverterTransformer implements ConfigurableTransformerInterface
{
    public function __construct(
        protected ConverterTransformer $converterTransformer,
    ) {
    }

    public function transform(mixed $value, array $options = []): array
    {
        if (!\is_array($value) && !$value instanceof \Traversable) {
            throw new \UnexpectedValueException('Input value must be an array or traversable');
        }
        $results = [];
        foreach ($value as $key => $item) {
            try {
                $item = $this->converterTransformer->transform($item, $options);
                if (null === $item && $options['skip_null']) {
                    continue;
                }
                $results[$key] = $item;
            } catch (TransformerException $exception) {
                $exception->setTargetProperty((string) $key);
                throw $exception;
            }
        }

        return $results;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $this->converterTransformer->configureOptions($resolver);
        $resolver->setDefaults(
            [
                'skip_null' => false,
            ]
        );
    }

    public function getCode(): string
    {
        return 'array_map_converter';
    }
}
