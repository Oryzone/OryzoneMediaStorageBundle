<?php

namespace Oryzone\Bundle\MediaStorageBundle\Context;

use Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

use Oryzone\Bundle\MediaStorageBundle\Context\Context;

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
     * Mostly used for caching purposes
     *
     * @var array $instances
     */
    protected $instances;

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
        $instances = array();
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
        if(isset($instances[$contextName]))

            return $instances[$contextName];

        if(!array_key_exists($contextName, $this->contexts))
            throw new \InvalidArgumentException(sprintf('The context "%s" has not been defined', $contextName));

        $c = $this->contexts[$contextName];
        $context = new Context($contextName, $c['provider'], $c['filesystem'], $c['cdn'], $c['namingStrategy'], $c['variants']);

        $instances[$contextName] = $context;

        return $context;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->contexts);
    }

}
