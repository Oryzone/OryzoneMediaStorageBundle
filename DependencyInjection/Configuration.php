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
    protected $defaultNamingStrategies = array(
        'default' => 'oryzone_media_storage.namingStrategies.slugged'
    );

    protected $defaultProviders = array(
        'default' => 'oryzone_media_storage.providers.file'
    );

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oryzone_media_storage');

        $this->addDbDriver($rootNode);
        $this->addNamingStrategies($rootNode);
        $this->addProviders($rootNode);
        $this->addCdns($rootNode);
        $this->addContexts($rootNode);

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

    protected function addNamingStrategies(ArrayNodeDefinition $root)
    {
        $root
        ->children()
            ->arrayNode('namingStrategies')
                ->useAttributeAsKey('id')
                ->prototype('scalar')->end()
                ->defaultValue($this->defaultNamingStrategies)
            ->end()
        ->end();
    }

    protected function addProviders(ArrayNodeDefinition $root)
    {
        $root
        ->children()
            ->arrayNode('providers')
                ->useAttributeAsKey('id')
                ->prototype('scalar')->end()
                ->defaultValue($this->defaultProviders)
            ->end()
        ->end();
    }

    protected function addCdns(ArrayNodeDefinition $root)
    {

    }

    protected function addContexts(ArrayNodeDefinition $root)
    {

    }
}
