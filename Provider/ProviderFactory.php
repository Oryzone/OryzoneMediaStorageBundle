<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ProviderFactory implements \IteratorAggregate
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @var array $providers
     */
    protected $providers;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $providers
     */
    public function __construct(ContainerInterface $container, $providers = array())
    {
        $this->container = $container;
        $this->providers = $providers;
    }

    /**
     * @param string $providerName
     *
     * @return ProviderInterface
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function get($providerName)
    {
        if(!array_key_exists($providerName, $this->providers))
            throw new \InvalidArgumentException(sprintf('The provider "%s" has not been defined', $providerName));

        $serviceName = $this->providers[$providerName];

        if(!$this->container->has($serviceName))
            throw new InvalidConfigurationException(sprintf('The service "%s" associated to the provider "%s" is not defined in the dependency injection container', $serviceName, $providerName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof ProviderInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the provider "%s" does not implement "Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface"', $serviceName, $providerName));

        return $service;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->providers);
    }

}
