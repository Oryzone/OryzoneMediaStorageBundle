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

        $dbDriver = $config['db_driver'];
        $container->setParameter('oryzone_media_storage.listener.doctrine.adapter.class', $this->adapterMap[$dbDriver]);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('naming_strategies.xml');
        $loader->load('providers.xml');
        $loader->load('media_storage.xml');
        $loader->load('orm.xml');
    }
}
