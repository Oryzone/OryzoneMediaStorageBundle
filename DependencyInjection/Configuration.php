<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oryzone_media_storage');

        $this->addDbDriver($rootNode);

        return $treeBuilder;
    }

    protected function addDbDriver(ArrayNodeDefinition $root)
    {
        $root
        ->children()
            ->scalarNode('db_driver')
                ->validate()
                    ->ifNotInArray(array('orm', 'mongodb'))
                    ->thenInvalid('Invalid database driver "%s". Allowed values: "orm", "mongodb"')
                ->end()
            ->end()
        ->end();
    }
}
