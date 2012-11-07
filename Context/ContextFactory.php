<?php

namespace Oryzone\Bundle\MediaStorageBundle\Context;

use Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ContextFactory implements \IteratorAggregate
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @var array $contexts
     */
    protected $contexts;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array                                                     $contexts
     */
    public function __construct(ContainerInterface $container, $contexts = array())
    {
        $this->container = $container;
        $this->contexts = $contexts;
    }

    /**
     * @param string $contextName
     *
     * @return ContextInterface
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function get($contextName)
    {
        if(!array_key_exists($contextName, $this->contexts))
            throw new \InvalidArgumentException(sprintf('The context "%s" has not been defined', $contextName));

        $serviceName = $this->contexts[$contextName];

        if(!$this->container->has($serviceName))
            throw new InvalidConfigurationException(sprintf('The service "%s" associated to the context "%s" is not defined in the dependency injection container', $serviceName, $contextName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof ContextInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the context "%s" does not implement "Oryzone\Bundle\MediaStorageBundle\Provider\ContextInterface"', $serviceName, $contextName));

        return $service;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->contexts);
    }

}
