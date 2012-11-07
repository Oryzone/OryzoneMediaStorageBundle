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
     */
    protected $cdns;

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

        $serviceName = $this->cdns[$cdnName];

        if(!$this->container->has($serviceName))
            throw new InvalidConfigurationException(sprintf('The service "%s" associated to the cdn "%s" is not defined in the dependency injection container', $serviceName, $cdnName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof CdnInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the cdn "%s" does not implement "Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface"', $serviceName, $cdnName));

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
