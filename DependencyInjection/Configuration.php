<?php

namespace Mesd\FilterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mesd_filter')
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('user_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('role_class')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('filter')
                    ->children()
                        ->arrayNode('bypass_roles')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                        ->arrayNode('filters')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->prototype('scalar')
                            ->end()
                        ->end()
                        ->arrayNode('template')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')
                                    ->defaultValue('MesdFilterBundle:Filter:index.html.twig')
                                ->end()
                                ->scalarNode('edit')
                                    ->defaultValue('MesdFilterBundle:Filter:edit.html.twig')
                                ->end()
                                ->scalarNode('new')
                                    ->defaultValue('MesdFilterBundle:Filter:new.html.twig')
                                ->end()
                                ->scalarNode('show')
                                    ->defaultValue('MesdFilterBundle:Filter:show.html.twig')
                                ->end()
                                ->scalarNode('solvent')
                                    ->defaultValue('MesdFilterBundle:Filter:solvent.html.twig')
                                ->end()
                                ->scalarNode('eachuser')
                                    ->defaultValue('MesdFilterBundle:Filter:eachuser.html.twig')
                                ->end()
                                ->scalarNode('allusers')
                                    ->defaultValue('MesdFilterBundle:Filter:allusers.html.twig')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
