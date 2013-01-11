<?php

namespace Oryzone\Bundle\MediaStorageBundle\NamingStrategy;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\DependencyInjection\ContainerInterface;

use Oryzone\MediaStorage\NamingStrategy\NamingStrategyFactoryInterface,
    Oryzone\MediaStorage\NamingStrategy\NamingStrategyInterface,
    Oryzone\MediaStorage\Exception\InvalidArgumentException,
    Oryzone\MediaStorage\Exception\InvalidConfigurationException;

class ContainerNamingStrategyFactory implements NamingStrategyFactoryInterface, \IteratorAggregate
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
     * {@inheritDoc}
     */
    public function get($namingStrategyName)
    {
        if(!array_key_exists($namingStrategyName, $this->aliases))
            throw new InvalidArgumentException(sprintf('The naming strategy "%s" has not been defined', $namingStrategyName));

        $serviceName = $this->aliases[$namingStrategyName];

        if(!$this->container->has($serviceName))
            throw new InvalidConfigurationException(sprintf('The service "%s" associated to the naming strategy "%s" is not defined in the dependency injection container', $serviceName, $namingStrategyName));

        $service = $this->container->get($serviceName);
        if(!$service instanceof NamingStrategyInterface)
            throw new InvalidConfigurationException(sprintf('The service "%s" associated with the naming strategy "%s" does not implement "Oryzone\MediaStorage\NamingStrategy\NamingStrategyInterface"', $serviceName, $namingStrategyName));

        return $service;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->aliases);
    }

}
