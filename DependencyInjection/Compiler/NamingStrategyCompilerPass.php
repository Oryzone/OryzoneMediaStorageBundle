<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class NamingStrategyCompilerPass implements CompilerPassInterface
{

    const NAMING_STRATEGY_FACTORY_SERVICE = 'oryzone_media_storage.naming_strategy_factory';
    const NAMING_STRATEGY_SERVICES_TAG = 'oryzone_media_storage_naming_strategy';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(self::NAMING_STRATEGY_FACTORY_SERVICE)) {
            return;
        }

        $definition = $container->getDefinition(self::NAMING_STRATEGY_FACTORY_SERVICE);

        foreach ($container->findTaggedServiceIds(self::NAMING_STRATEGY_SERVICES_TAG) as $id => $attributes) {
            if(!isset($attributes[0]['alias']))
                throw new InvalidConfigurationException(sprintf('Service "%s" lacks of mandatory "alias" attribute for service tagged as "%s"', $id, self::NAMING_STRATEGY_SERVICES_TAG));

            $definition->addMethodCall('addAlias', array($id, $attributes[0]['alias']));
        }
    }
}
