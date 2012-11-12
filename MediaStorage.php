<?php

namespace Oryzone\Bundle\MediaStorageBundle;

use Knp\Bundle\GaufretteBundle\FilesystemMap;

use Oryzone\Bundle\MediaStorageBundle\Cdn\CdnFactory,
    Oryzone\Bundle\MediaStorageBundle\Context\ContextFactory,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderFactory,
    Oryzone\Bundle\MediaStorageBundle\NamingStrategy\NamingStrategyFactory,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

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
     * Loads a cdn with a given name
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default cdn
     * @return Cdn\CdnInterface
     * @throws Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface
     */
    public function getCdn($name = NULL)
    {
        if (!$name) {
            if(!$this->defaultCdn)
                throw new InvalidArgumentException('Trying to load the default CDN but a it has not been set');
            $name = $this->defaultCdn;
        }

        return $this->cdnFactory->get($name);
    }

    /**
     * Loads a context with a given name
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default context
     * @return Context\ContextInterface
     * @throws Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Context\ContextInterface
     */
    public function getContext($name = NULL)
    {
        if (!$name) {
            if(!$this->defaultContext)
                throw new InvalidArgumentException('Trying to load the default Context but it has not been set');

            $name = $this->defaultContext;
        }

        return $this->contextFactory->get($name);
    }

    /**
     * Loads a filesystem with a given filesystem
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default filesystem
     * @return \Gaufrette\Filesystem
     * @throws Exception\InvalidArgumentException
     *
     * @return \Gaufrette\Filesystem
     */
    public function getFilesystem($name = NULL)
    {
        if (!$name) {
            if(!$this->defaultFilesystem)
                throw new InvalidArgumentException('Trying to load the default Filesystem but it has not been set');

            $name = $this->defaultFilesystem;
        }

        return $this->filesystemMap->get($name);
    }

    /**
     * Loads a provider with a given name
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default provider
     * @return Provider\ProviderInterface
     * @throws Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface
     */
    public function getProvider($name = NULL)
    {
        if (!$name) {
            if(!$this->defaultProvider)
                throw new InvalidArgumentException('Trying to load the default Provider but it has not been set');

            $name = $this->defaultProvider;
        }

        return $this->providerFactory->get($name);
    }

    /**
     * Loads a naming strategy with a given name
     *
     * @param  string|null                        $name
     * @throws Exception\InvalidArgumentException
     *
     * @return NamingStrategy\NamingStrategyInterface
     */
    public function getNamingStrategy($name = NULL)
    {
        if (!$name) {
            if(!$this->defaultNamingStrategy)
                throw new InvalidArgumentException('Trying to load the default Naming strategy but it has not been set');

            $name = $this->defaultNamingStrategy;
        }

        return $this->namingStrategyFactory->get($name);
    }

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media, $isUpdate = false)
    {
        $provider = $this->getProvider($media->getProvider());
        if(!$media->getProvider())
            $media->setProvider($provider->getName());
        $context = $this->getContext($media->getContent());
        if(!$media->getContext())
            $media->setContext($context->getName());
        $provider->prepare($media, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function saveMedia(Media $media)
    {
        $provider = $this->getProvider($media->getProvider());
        $context = $this->getContext($media->getContext());
        $variantsTree = $context->buildVariantTree();

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
