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

namespace Sidus\ConverterBundle\DependencyInjection;

use Sidus\ConverterBundle\Utility\TypeUtility;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\NodeInterface;

/**
 * Expose a configuration to generate ConverterConfiguration(s) in cache.
 */
class Configuration implements ConfigurationInterface
{
    protected ?NodeInterface $nestedTree = null;

    /**
     * This method can be used by components who want to expose a converter configuration.
     */
    public function buildNestedConfiguration(array $v): array
    {
        if (!$this->nestedTree) {
            $treeBuilder = new TreeBuilder('converter');
            $this->buildConverterConfiguration($treeBuilder->getRootNode()->children())->end();
            $this->nestedTree = $treeBuilder->buildTree();
        }

        return $this->nestedTree->finalize($this->nestedTree->normalize($v));
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sidus_converter');

        /* @formatter:off */
        $nodeBuilder = $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('configurations')
                    ->useAttributeAsKey('code')
                    ->arrayPrototype()
                        ->performNoDeepMerging()
                        ->children();
        $nodeBuilder = $this->buildConverterConfiguration($nodeBuilder)
                        ->end()
                    ->end()
                ?->end()
                ->arrayNode('behaviors')
                    ->useAttributeAsKey('code')
                    ->arrayPrototype()
                        ->performNoDeepMerging()
                        ->children();
        $this->buildCommonMappingConfiguration($nodeBuilder)
                        ->end()
                    ->end()
                ?->end()
            ->end();
        /* @formatter:on */

        return $treeBuilder;
    }

    protected function buildConverterConfiguration(NodeBuilder $nodeBuilder): NodeBuilder
    {
        /* @formatter:off */
        $nodeBuilder
            ?->booleanNode('skip_null')->defaultFalse()->end()
            ?->booleanNode('hydrate_object')->defaultFalse()->end()
            ?->booleanNode('auto_mapping')->defaultFalse()->end()
            ?->arrayNode('behaviors')
                ->defaultValue([])
                ->scalarPrototype()->end()
            ?->end();
        /* @formatter:on */

        return $this->buildCommonMappingConfiguration($nodeBuilder);
    }

    protected function buildCommonMappingConfiguration(NodeBuilder $nodeBuilder): NodeBuilder
    {
        /* @formatter:off */
        return $nodeBuilder
            ->scalarNode('output_type')
                ->isRequired()
                ->cannotBeEmpty()
                ->beforeNormalization()
                    ->always(TypeUtility::normalizeType(...))
                ->end()
            ->end()
            ?->scalarNode('input_type')
                ->isRequired()
                ->cannotBeEmpty()
                ->beforeNormalization()
                    ->always(TypeUtility::normalizeType(...))
                ->end()
            ->end()
            ?->booleanNode('ignore_all_missing')->defaultFalse()->end()
            ?->arrayNode('accessor')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('exception_on_invalid_index')->defaultTrue()->end()
                    ?->booleanNode('exception_on_invalid_property_path')->defaultTrue()->end()
                    ?->booleanNode('enable_magic_call')->defaultFalse()->end()
                    ?->booleanNode('enable_magic_get')->defaultTrue()->end()
                    ?->booleanNode('enable_magic_set')->defaultTrue()->end()
                ?->end()
            ->end()
            ->arrayNode('mapping')
                ->useAttributeAsKey('output_property')
                ->defaultValue([])
                ->performNoDeepMerging()
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('input_property')->defaultNull()->end()
                        ?->booleanNode('ignore_missing')->defaultNull()->end()
                        ?->booleanNode('ignored')->defaultFalse()->end()
                        ?->arrayNode('transformers')
                            ->arrayPrototype()
                                ->variablePrototype()->end()
                            ?->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        /* @formatter:on */
    }
}
