<?php

namespace App\Boa\ProductBundle\DependencyInjection;

use App\Boa\ProductBundle\Form\Type;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('boa_product');

        $supportedDrivers = array('orm');

        $rootNode
            ->children()
                ->scalarNode('product_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('model_manager_name')->defaultNull()->end()
            ->end()
        ;

        $this->addCreateSection($rootNode);
        $this->addServiceSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addCreateSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('create')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('form')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue(Type\CreateFormType::class)->end()
                                ->scalarNode('name')->defaultValue('boa_product_create_form')->end()
                                ->arrayNode('validation_groups')
                                    ->prototype('scalar')->end()
                                    ->defaultValue(array('Create', 'Default'))
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addServiceSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('service')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('product_manager')->defaultValue('boa_product.product_manager.default')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
