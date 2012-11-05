<?php

namespace Oryzone\Bundle\MediaStorageBundle;

use Knp\Bundle\GaufretteBundle\FilesystemMap;

use Oryzone\Bundle\MediaStorageBundle\Cdn\CdnFactory,
    Oryzone\Bundle\MediaStorageBundle\Context\ContextFactory,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderFactory,
    Oryzone\Bundle\MediaStorageBundle\Model\Media;

/**
 * Base media storage class
 */
class MediaStorage implements MediaStorageInterface
{
    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Cdn\CdnFactory $cdnFactory
     */
    protected $cdnFactory;

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Context\ContextFactory $contextFactory
     */
    protected $contextFactory;

    /**
     * @var \Knp\Bundle\GaufretteBundle\FilesystemMap $filesystemMap
     */
    protected $filesystemMap;

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderFactory $providerFactory
     */
    protected $providerFactory;


    /**
     * Constructor
     *
     * @param Cdn\CdnFactory $cdnFactory
     * @param Context\ContextFactory $contextFactory
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap $filesystemMap
     * @param Provider\ProviderFactory $providerFactory
     */
    function __construct(CdnFactory $cdnFactory, ContextFactory $contextFactory, FilesystemMap $filesystemMap, ProviderFactory $providerFactory)
    {
        $this->cdnFactory = $cdnFactory;
        $this->contextFactory = $contextFactory;
        $this->filesystemMap = $filesystemMap;
        $this->providerFactory = $providerFactory;
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
