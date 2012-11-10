<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class OryzoneMediaStorageExtension extends Extension
{

    protected $adapterMap = array(
        'orm'     => 'Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\ORM\DoctrineORMAdapter',
        'mongodb' => 'Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\ODM\MongoDB\MongoDBAdapter'
    );

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $toLoad = array('cdns.xml', 'naming_strategies.xml', 'providers.xml', 'media_storage.xml', 'orm.xml');
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        foreach($toLoad as $file)
            $loader->load($file);

        // sets parameters from global config
        $dbDriver = $config['db_driver'];
        $container->setParameter('oryzone_media_storage.listener.doctrine.adapter.class', $this->adapterMap[$dbDriver]);

        $container->setParameter('oryzone_media_storage.cdn_factory.cdns', $config['cdns']);
        $container->setParameter('oryzone_media_storage.context_factory.contexts', $config['contexts']);
        $container->setParameter('oryzone_media_storage.provider_factory.providers', $config['providers']);
        $container->setParameter('oryzone_media_storage.naming_strategy_factory.naming_strategies', $config['namingStrategies']);

        $container->setParameter('oryzone_media_storage.default_cdn', $config['defaultCdn']);
        $container->setParameter('oryzone_media_storage.default_context', $config['defaultContext']);
        $container->setParameter('oryzone_media_storage.default_filesystem', $config['defaultFilesystem']);
        $container->setParameter('oryzone_media_storage.default_provider', $config['defaultProvider']);
        $container->setParameter('oryzone_media_storage.default_naming_strategy', $config['defaultNamingStrategy']);
    }
}
