<?php

namespace Oryzone\Bundle\MediaStorageBundle\Cdn;

use Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class CdnFactory implements \IteratorAggregate
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @var array $cdns
     *
     * Must follow this structure
     * array(
     *      'cdnName' => array(
     *          'cdnType' =>  array( <options> )
     *      )
     * )
     */
    protected $cdns;

    /**
     * Array containing aliases for cdn services
     *
     * E.g.
     *
     * array(
     *  'remote'    => 'oryzone_media_storage.cdns.remote',
     *  'local'     => 'oryzone_media_storage.cdns.local'
     * )
     *
     * @var array $cdnAliases
     */
    protected $aliases;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array                                                     $cdns
     */
    public function __construct(ContainerInterface $container, $cdns = array())
    {
        $this->container = $container;
        $this->cdns = $cdns;
        $this->aliases = array();
    }

    /**
     * Adds a service alias
     *
     * @param string $serviceName the name of the service in the DIC
     * @param string $alias       the alias
     */
    public function addAlias($serviceName, $alias)
    {
        $this->aliases[$alias] = $serviceName;
    }

    /**
     * @param $cdnName
     * @return CdnInterface
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function get($cdnName)
    {
        if(!array_key_exists($cdnName, $this->cdns))
            throw new \InvalidArgumentException(sprintf('The cdn "%s" has not been defined', $cdnName));

        $cdn = $this->cdns[$cdnName];
        reset($cdn);
        $cdnPlainArray = each($cdn);
        $cdnAlias = $cdnPlainArray['key'];
        $cdnConfiguration = $cdnPlainArray['value'];

        if(!isset($this->aliases[$cdnAlias]))
            throw new InvalidConfigurationException(sprintf('No CDN service defined with the alias "%s". Your confiuration has: %s', $cdnAlias, json_encode($cdn)));

        $serviceName = $this->aliases[$cdnAlias];

        if(!$this->container->has($serviceName))
            throw new InvalidConfigurationException(sprintf('The service "%s" associated to the cdn "%s" is not defined in the dependency injection container', $serviceName, $cdnName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof CdnInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the cdn "%s" does not implement "Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface"', $serviceName, $cdnName));

        $service->setConfiguration($cdnConfiguration);

        return $service;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->cdns);
    }
}
