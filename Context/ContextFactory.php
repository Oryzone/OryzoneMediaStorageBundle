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

        $c = $this->contexts[$contextName];
        $context = new Context($contextName, $c['provider'], $c['filesystem'], $c['cdn'], $c['variants']);

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
