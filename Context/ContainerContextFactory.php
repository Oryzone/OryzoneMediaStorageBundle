<?php

namespace Oryzone\Bundle\MediaStorageBundle\Context;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\DependencyInjection\ContainerInterface;

use Oryzone\MediaStorage\Context\ContextFactoryInterface,
    Oryzone\MediaStorage\Context\Context,
    Oryzone\MediaStorage\Exception\InvalidArgumentException;

class ContainerContextFactory implements ContextFactoryInterface, \IteratorAggregate
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
     * {@inheritDoc}
     */
    public function get($contextName)
    {
        if(isset($instances[$contextName]))

            return $instances[$contextName];

        if(!array_key_exists($contextName, $this->contexts))
            throw new InvalidArgumentException(sprintf('The context "%s" has not been defined', $contextName));

        $c = $this->contexts[$contextName];
        $providerName = key($c['provider']);
        $providerOptions = $c['provider'][$providerName];
        $context = new Context($contextName, $providerName, $providerOptions, $c['filesystem'], $c['cdn'],
                            $c['namingStrategy'], $c['variants'], $c['defaultVariant']);

        $instances[$contextName] = $context;

        return $context;
    }

    /**
     * Gets all the available contexts
     *
     * @return array
     */
    public function getAvailableContexts()
    {
        return array_keys($this->contexts);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->contexts);
    }

}
