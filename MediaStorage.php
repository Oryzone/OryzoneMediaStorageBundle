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
     * @var NamingStrategy\NamingStrategyFactory $namingStrategyFactory
     */
    protected $namingStrategyFactory;

    /**
     * @var null|string $defaultCdn
     */
    protected $defaultCdn;

    /**
     * @var null|string $defaultContext
     */
    protected $defaultContext;

    /**
     * @var null|string $defaultFilesystem
     */
    protected $defaultFilesystem;

    /**
     * @var null|string $defaultProvider
     */
    protected $defaultProvider;

    /**
     * @var null|string $defaultNamingStrategy
     */
    protected $defaultNamingStrategy;

    /**
     * Constructor
     *
     * @param Cdn\CdnFactory                            $cdnFactory
     * @param Context\ContextFactory                    $contextFactory
     * @param \Knp\Bundle\GaufretteBundle\FilesystemMap $filesystemMap
     * @param Provider\ProviderFactory                  $providerFactory
     * @param NamingStrategy\NamingStrategyFactory      $namingStrategyFactory
     * @param string|null                               $defaultCdn
     * @param string|null                               $defaultContext
     * @param string|null                               $defaultFilesystem
     * @param string|null                               $defaultProvider
     * @param string|null                               $defaultNamingStrategy
     */
    public function __construct(CdnFactory $cdnFactory, ContextFactory $contextFactory, FilesystemMap $filesystemMap,
                                ProviderFactory $providerFactory, NamingStrategyFactory $namingStrategyFactory,
                                $defaultCdn = NULL, $defaultContext = NULL, $defaultFilesystem = NULL,
                                $defaultProvider = NULL, $defaultNamingStrategy = NULL)
    {
        $this->cdnFactory = $cdnFactory;
        $this->contextFactory = $contextFactory;
        $this->filesystemMap = $filesystemMap;
        $this->providerFactory = $providerFactory;
        $this->namingStrategyFactory = $namingStrategyFactory;
        $this->defaultCdn = $defaultCdn;
        $this->defaultContext = $defaultContext;
        $this->defaultFilesystem = $defaultFilesystem;
        $this->defaultProvider = $defaultProvider;
        $this->defaultNamingStrategy = $defaultNamingStrategy;
    }

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media, $isUpdate = false)
    {
        // TODO: Implement prepareMedia() method.
        //W.I.P.
        //$provider = $this->providerFactory->get($media->getProvider());
        //$provider->prepare($media);
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
