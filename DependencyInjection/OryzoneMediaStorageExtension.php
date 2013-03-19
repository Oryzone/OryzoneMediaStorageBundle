<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader,
    Symfony\Component\DependencyInjection\Alias;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OryzoneMediaStorageExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $toLoad = array('cdn.xml', 'context.xml', 'downloader.xml', 'event.xml', 'filesystem.xml', 'form.xml',
            'integration.xml', 'media_storage.xml', 'naming_strategy.xml', 'persistence.xml', 'provider.xml', 'templating.xml');
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        foreach($toLoad as $file)
            $loader->load($file);

        $container->setParameter('oryzone_media_storage.cdn.cdn_factory.cdns', $config['cdns']);
        $container->setParameter('oryzone_media_storage.context.context_factory.contexts', $config['contexts']);

        $container->setParameter('oryzone_media_storage.default_cdn', $config['defaultCdn']);
        $container->setParameter('oryzone_media_storage.default_context', $config['defaultContext']);
        $container->setParameter('oryzone_media_storage.default_filesystem', $config['defaultFilesystem']);
        $container->setParameter('oryzone_media_storage.default_provider', $config['defaultProvider']);
        $container->setParameter('oryzone_media_storage.default_naming_strategy', $config['defaultNamingStrategy']);
        $container->setParameter('oryzone_media_storage.default_variant', $config['defaultVariant']);
    }
}
