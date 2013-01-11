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

class CdnCompilerPass implements CompilerPassInterface
{

    const CDN_FACTORY_SERVICE = 'oryzone_media_storage.cdn.container_cdn_factory';
    const CDN_SERVICES_TAG = 'oryzone_media_storage_cdn';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(self::CDN_FACTORY_SERVICE)) {
            throw new InvalidConfigurationException(sprintf('The cdn factory service ("%s") is missing', self::CDN_FACTORY_SERVICE));
        }

        $definition = $container->getDefinition(self::CDN_FACTORY_SERVICE);

        foreach ($container->findTaggedServiceIds(self::CDN_SERVICES_TAG) as $id => $attributes) {
            if(!isset($attributes[0]['alias']))
                throw new InvalidConfigurationException(sprintf('Service "%s" needs mandatory "alias" attribute for service tagged as "%s"', $id, self::CDN_SERVICES_TAG));

            $definition->addMethodCall('addAlias', array($id, $attributes[0]['alias']));
        }
    }
}
