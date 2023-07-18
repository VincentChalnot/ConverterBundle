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

namespace Sidus\ConverterBundle\Model\Mapping;

use CleverAge\ProcessBundle\Transformer\ConfigurableTransformerInterface;
use CleverAge\ProcessBundle\Transformer\TransformerInterface;
use Sidus\ConverterBundle\Model\Event\EventInterface;
use Sidus\ConverterBundle\Transformer\ConverterTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Configures a transformer for runtime.
 */
class TransformerConfiguration
{
    protected bool $isResolved = false;

    public function __construct(
        protected TransformerInterface $transformer,
        protected array $options = [],
    ) {
    }

    public function transform(EventInterface $event, Mapping $mapping, mixed $value): mixed
    {
        if ($this->transformer instanceof ConverterTransformer) {
            return $this->transformer->transformWithParent($event, $mapping, $value, $this->getOptions());
        }

        return $this->transformer->transform($value, $this->getOptions());
    }

    public function getTransformer(): TransformerInterface
    {
        return $this->transformer;
    }

    public function getOptions(): array
    {
        if (!$this->isResolved) {
            $transformer = $this->getTransformer();
            if ($transformer instanceof ConfigurableTransformerInterface) {
                $resolver = new OptionsResolver();
                $transformer->configureOptions($resolver);
                $this->options = $resolver->resolve($this->options);
            }
            $this->isResolved = true;
        }

        return $this->options;
    }
}
