<?php

namespace Oryzone\Bundle\MediaStorageBundle;

use Knp\Bundle\GaufretteBundle\FilesystemMap;

use Oryzone\Bundle\MediaStorageBundle\Cdn\CdnFactory,
    Oryzone\Bundle\MediaStorageBundle\Context\ContextFactory,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderFactory,
    Oryzone\Bundle\MediaStorageBundle\NamingStrategy\NamingStrategyFactory,
    Oryzone\Bundle\MediaStorageBundle\Model\Media;

/**
 * Base media storage class
 */
class MediaStorage implements MediaStorageInterface
{
    /**
     * @var Cdn\CdnFactory $cdnFactory
     */
    protected $cdnFactory;

    /**
     * @var Context\ContextFactory $contextFactory
     */
    protected $contextFactory;

    /**
     * @var \Knp\Bundle\GaufretteBundle\FilesystemMap $filesystemMap
     */
    protected $filesystemMap;

    /**
     * @var Provider\ProviderFactory $providerFactory
     */
    protected $providerFactory;

    /**
     * @var NamingStrategy\NamingStrategyFactory $
     */
    protected $namingStrategyFactory;


    /**
     * Constructor
     *
     * @param Cdn\CdnFactory $cdnFactory
     * @param Context\ContextFactory $contextFactory
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap $filesystemMap
     * @param Provider\ProviderFactory $providerFactory
     * @param NamingStrategy\NamingStrategyFactory $namingStrategyFactory
     */
    function __construct(CdnFactory $cdnFactory, ContextFactory $contextFactory, FilesystemMap $filesystemMap,
                         ProviderFactory $providerFactory, NamingStrategyFactory $namingStrategyFactory)
    {
        $this->cdnFactory = $cdnFactory;
        $this->contextFactory = $contextFactory;
        $this->filesystemMap = $filesystemMap;
        $this->providerFactory = $providerFactory;
        $this->namingStrategyFactory = $namingStrategyFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media)
    {
        // TODO: Implement prepareMedia() method.
    }

    /**
     * {@inheritDoc}
     */
    public function saveMedia(Media $media)
    {
        // TODO: Implement saveMedia() method.
    }

    /**
     * {@inheritDoc}
     */
    public function updateMedia(Media $media)
    {
        // TODO implement updateMedia() method
    }

    /**
     * {@inheritDoc}
     */
    public function removeMedia(Media $media)
    {
        // TODO: Implement removeMedia() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getPath(Media $media)
    {
        // TODO: Implement getPath() method.
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(Media $media)
    {
        // TODO: Implement getUrl() method.
    }
}
