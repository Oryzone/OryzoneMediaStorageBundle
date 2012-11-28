<?php

namespace Oryzone\Bundle\MediaStorageBundle;

use Knp\Bundle\GaufretteBundle\FilesystemMap;

use Gaufrette\StreamMode,
    Gaufrette\Stream\Local;

use Symfony\Component\HttpFoundation\File\File;

use Oryzone\Bundle\MediaStorageBundle\Cdn\CdnFactory,
    Oryzone\Bundle\MediaStorageBundle\Context\ContextFactory,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderFactory,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface,
    Oryzone\Bundle\MediaStorageBundle\NamingStrategy\NamingStrategyFactory,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantNode,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException,
    Oryzone\Bundle\MediaStorageBundle\Exception\VariantProcessingException;

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
     * @var null|string $defaultVariant
     */
    protected $defaultVariant;

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
     * @param string|null                               $defaultVariant
     */
    public function __construct(CdnFactory $cdnFactory, ContextFactory $contextFactory, FilesystemMap $filesystemMap,
                                ProviderFactory $providerFactory, NamingStrategyFactory $namingStrategyFactory,
                                $defaultCdn = NULL, $defaultContext = NULL, $defaultFilesystem = NULL,
                                $defaultProvider = NULL, $defaultNamingStrategy = NULL, $defaultVariant = NULL)
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
        $this->defaultVariant = $defaultVariant;
    }

    /**
     * Creates a Gaufrette File instance from a source.
     * Source may be a string (of a path) an instance of SPL <code>File</code> or
     * <code>Symfony\Component\HttpFoundation\File\UploadedFile</code>
     *
     * @param  Model\Media                          $media
     * @param  Variant\VariantInterface             $variant
     * @throws Exception\VariantProcessingException
     *
     * @return File
     */
    protected function createFileInstance(Media $media, VariantInterface $variant)
    {
        $source = $media->getContent();

        if (is_string($source)) {
            if(!is_file($source))
                throw new VariantProcessingException(
                    sprintf('Cannot load file "%s" for media "%s", variant "%s". File not found.', $source, $media, $variant->getName()), $media, $variant);

            return new File($source);
        } elseif(is_object($source) && $source instanceof File)

            return $source;

        throw new VariantProcessingException(
            sprintf('cannot load file for media "%s", variant "%s"', $media, $variant->getName()), $media, $variant);
    }

    /**
     * @param File                     $file
     * @param string                   $filename
     * @param \Gaufrette\Filesystem    $filesystem
     * @param Variant\VariantInterface $variant
     *
     * @return string
     */
    protected function storeFile(File $file, $filename, \Gaufrette\Filesystem $filesystem, VariantInterface $variant)
    {
        $extension = $file->getExtension();
        $filename .= '.'.$extension;

        $src = new Local($file->getPathname());

        //$dst = $filesystem->getAdapter()->createFileStream($filename, $filesystem);
        $dst = $filesystem->createStream($filename);

        $src->open(new StreamMode('rb+'));
        $dst->open(new StreamMode('ab+'));

        while (!$src->eof()) {
            $data    = $src->read(100000);
            $written = $dst->write($data);
        }
        $dst->close();
        $src->close();

        $variant->setFilename($filename);
        $variant->setContentType($file->getMimeType());
        $variant->setStatus(VariantInterface::STATUS_READY);

        return $filename;
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
     * @param null $name
     * @throws Exception\InvalidArgumentException
     * @param  string|null                        $name
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\NamingStrategy\NamingStrategyInterface
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
     * Get the name of a variant (fallbacks to default if null is given)
     *
     * @param string|null $name
     * @return null|string
     * @throws Exception\InvalidArgumentException
     */
    public function getVariant($name = NULL)
    {
        if($name == NULL)
        {
            if(!$this->defaultVariant)
                throw new InvalidArgumentException('Trying to use the default variant but it has not been set');

            $name = $this->defaultVariant;
        }

        return $name;
    }

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media, $isUpdate = false)
    {
        $provider = $this->getProvider($media->getProvider());
        if(!$media->getProvider())
            $media->setProvider($provider->getName());
        $context = $this->getContext($media->getContext());
        if(!$media->getContext())
            $media->setContext($context->getName());
        $provider->prepare($media, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function saveMedia(Media $media)
    {
        $context = $this->getContext($media->getContext());
        $provider = $this->getProvider($context->getProviderName());
        $variantsTree = $context->buildVariantTree();
        $filesystem = $this->getFilesystem($context->getFilesystemName());
        $namingStrategy = $this->getNamingStrategy($context->getNamingStrategyName());

        $generatedFiles = array();

        $variantsTree->visit(
            function(VariantNode $node, $level)
                use ($provider, $context, $media, $filesystem, $namingStrategy, &$generatedFiles)
            {
                $variant = $node->getContent();
                $parent = $node->getParent() ? $node->getParent()->getContent() : NULL;
                $media->addVariant($variant);

                $file = NULL;
                if ($provider->getContentType() == ProviderInterface::CONTENT_TYPE_FILE) {
                    if ($parent) {
                        // checks if the parent file has been generated in a previous step
                        if(isset($generatedFiles[$parent->getName()]))
                            $file = $generatedFiles[$parent->getName()];
                        else {
                            //otherwise try to read the file from the storage if the variant is ready
                            //TODO

                            throw new VariantProcessingException(
                                sprintf('Cannot load parent variant ("%s") file for variant "%s" of media "%s"', $parent->getName(), $variant->getName(), $media),
                                $media, $variant);
                        }

                    } else
                        $file = $this->createFileInstance($media, $variant);
                }

                switch ($variant->getMode()) {
                    case VariantInterface::MODE_INSTANT:
                        $result = $provider->process($media, $variant, $file);
                        if ($result) {
                            $generatedFiles[$variant->getName()] = $result;
                            $name = $namingStrategy->generateName($media, $variant, $filesystem);
                            $this->storeFile($result, $name, $filesystem, $variant);
                        }
                        break;

                    case VariantInterface::MODE_LAZY:
                        // TODO
                        break;

                    case VariantInterface::MODE_QUEUE:
                        // TODO
                        break;
                }

                //updates the variant in the media (to store the new values)
                $media->addVariant($variant);
            }
        );

        $provider->removeTempFiles();
        return TRUE; // marks the media as updated
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
        //TODO make removal of physical files asynchronous (optionally)
        $context = $this->getContext($media->getContext());
        $filesystem = $this->getFilesystem($context->getFilesystemName());

        foreach($media->getVariants() as $name => $value)
        {
            $variant = $media->getVariantInstance($name);
            if($variant->isReady())
                $filesystem->delete($variant->getFilename());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(Media $media, $variant = NULL, $options = array())
    {
        $context = $this->getContext($media->getContext());
        $cdn = $this->getCdn($context->getCdnName());
        $variant = $media->getVariantInstance($variant);
        return $cdn->getUrl($media, $variant, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, $variant = NULL, $options = array())
    {
        if($variant === NULL)
            $variant = $this->defaultVariant;

        $context = $this->getContext($media->getContext());
        $provider = $this->getProvider($context->getProviderName());
        $variantInstance = $media->getVariantInstance($variant);

        $urlOptions = array();
        if(isset($options['_url']))
            $urlOptions = $options['_url'];

        return $provider->render($media, $variantInstance, $this->getUrl($media, $variant, $urlOptions), $options);
    }
}
