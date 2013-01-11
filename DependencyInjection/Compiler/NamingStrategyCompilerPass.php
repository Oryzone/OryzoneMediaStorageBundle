<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection\Compiler;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class NamingStrategyCompilerPass implements CompilerPassInterface
{

    const NAMING_STRATEGY_FACTORY_SERVICE = 'oryzone_media_storage.naming_strategy.container_naming_strategy_factory';
    const NAMING_STRATEGY_SERVICES_TAG = 'oryzone_media_storage_naming_strategy';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(self::NAMING_STRATEGY_FACTORY_SERVICE)) {
            throw new InvalidConfigurationException(sprintf('The naming strategy factory service ("%s") is missing', self::NAMING_STRATEGY_FACTORY_SERVICE));
        }

        $definition = $container->getDefinition(self::NAMING_STRATEGY_FACTORY_SERVICE);

        foreach ($container->findTaggedServiceIds(self::NAMING_STRATEGY_SERVICES_TAG) as $id => $attributes) {
            if(!isset($attributes[0]['alias']))
                throw new InvalidConfigurationException(sprintf('Service "%s" needs mandatory "alias" attribute for service tagged as "%s"', $id, self::NAMING_STRATEGY_SERVICES_TAG));

            $definition->addMethodCall('addAlias', array($id, $attributes[0]['alias']));
        }
    }
}
