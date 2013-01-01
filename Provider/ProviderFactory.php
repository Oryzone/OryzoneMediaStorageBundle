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
     * @var array $aliases
     */
    protected $aliases;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->aliases = array();
    }

    /**
     * Add a service alias
     *
     * @param string $serviceName
     * @param string $alias
     */
    public function addAlias($serviceName, $alias)
    {
        $this->aliases[$alias] = $serviceName;
    }

    /**
     * @param string $providerName
     * @param array $providerOptions
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     *
     * @return ProviderInterface
     */
    public function get($providerName, $providerOptions = array())
    {
        if(!array_key_exists($providerName, $this->aliases))
            throw new \InvalidArgumentException(sprintf('The provider "%s" has not been defined', $providerName));

        $serviceName = $this->aliases[$providerName];

        if(!$this->container->has($serviceName))
            throw new InvalidConfigurationException(sprintf('The service "%s" associated to the provider "%s" is not defined in the dependency injection container', $serviceName, $providerName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof ProviderInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the provider "%s" does not implement "Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface"', $serviceName, $providerName));

        $service->setOptions($providerOptions);
        return $service;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->aliases);
    }

}
