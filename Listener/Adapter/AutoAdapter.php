<?php

namespace Oryzone\Bundle\MediaStorageBundle\Listener\Adapter;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\EventArgs;

use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class AutoAdapter implements AdapterInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    protected $container;

    /**
     * @var array $adaptersMap
     */
    protected $adaptersMap;

    /**
     * @var array $cache
     */
    protected $cache;

    /**
     * Constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $adaptersMap
     */
    public function __construct(ContainerInterface $container, $adaptersMap)
    {
        $this->container = $container;
        $this->adaptersMap = $adaptersMap;
        $this->cache = array();
    }

    /**
     * Get the correct adapter for the current event
     *
     * @param \Doctrine\Common\EventArgs $e
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\AdapterInterface
     */
    protected function getAdapter(EventArgs $e)
    {
        $adapterService = NULL;
        $eventClass = get_class($e);

        if(isset($this->cache[$eventClass]))
            return $this->cache[$eventClass];

        foreach($this->adaptersMap as $mappedClass => $service)
        {
            if($e instanceof $mappedClass)
            {
                $adapterService = $service;
                break;
            }
        }

        if($adapterService == NULL)
            throw new InvalidArgumentException(sprintf('Can\'t find appropriate adapter for event of class "%s". You must add this class (or a subclass) in the adapters mapping for the AutoAdapter configuration', $eventClass));

        $adapter = $this->container->get($adapterService);
        $this->cache[$eventClass] = $adapter;

        return $adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function getObjectFromArgs(EventArgs $e)
    {
        return $this->getAdapter($e)->getObjectFromArgs($e);
    }

    /**
     * {@inheritDoc}
     */
    public function getManagerFromArgs(EventArgs $e)
    {
        return $this->getAdapter($e)->getManagerFromArgs($e);
    }

    /**
     * {@inheritDoc}
     */
    public function recomputeChangeSet(EventArgs $e)
    {
        return $this->getAdapter($e)->recomputeChangeSet($e);
    }
}
