<?php

namespace Oryzone\Bundle\MediaStorageBundle\NamingStrategy;

use Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class NamingStrategyFactory implements \IteratorAggregate
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @var array $namingStrategies
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
     * Adds a service alias
     *
     * @param string $serviceName
     * @param string $alias
     */
    public function addAlias($serviceName, $alias)
    {
        $this->aliases[$alias] = $serviceName;
    }

    /**
     * @param string $namingStrategyName
     *
     * @return NamingStrategyInterface
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function get($namingStrategyName)
    {
        if(!array_key_exists($namingStrategyName, $this->aliases))
            throw new \InvalidArgumentException(sprintf('The naming strategy "%s" has not been defined', $namingStrategyName));

        $serviceName = $this->aliases[$namingStrategyName];

        if(!$this->container->has($serviceName))
            throw new InvalidConfigurationException(sprintf('The service "%s" associated to the naming strategy "%s" is not defined in the dependency injection container', $serviceName, $namingStrategyName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof NamingStrategyInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the naming strategy "%s" does not implement "Oryzone\Bundle\MediaStorageBundle\NamingStrategy\NamingStrategyInterface"', $serviceName, $namingStrategyName));

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
