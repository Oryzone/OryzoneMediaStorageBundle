<?php

namespace Oryzone\Bundle\MediaStorageBundle;

use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidContentException;

use Gaufrette\StreamMode,
    Gaufrette\Stream\Local;

use Symfony\Component\HttpFoundation\File\File,
    Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Oryzone\Bundle\MediaStorageBundle\Cdn\CdnFactory,
    Oryzone\Bundle\MediaStorageBundle\Context\ContextFactory,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderFactory,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface,
    Oryzone\Bundle\MediaStorageBundle\NamingStrategy\NamingStrategyFactory,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantNode,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException,
    Oryzone\Bundle\MediaStorageBundle\Exception\VariantProcessingException,
    Oryzone\Bundle\MediaStorageBundle\Event\MediaEvents,
    Oryzone\Bundle\MediaStorageBundle\Event\MediaEvent;


/**
 * Base media storage class
 */
class MediaStorage implements MediaStorageInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    protected $eventDispatcher;

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
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
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
    public function __construct(EventDispatcherInterface $eventDispatcher, CdnFactory $cdnFactory,
                                ContextFactory $contextFactory, FilesystemMap $filesystemMap,
                                ProviderFactory $providerFactory, NamingStrategyFactory $namingStrategyFactory,
                                $defaultCdn = NULL, $defaultContext = NULL, $defaultFilesystem = NULL,
                                $defaultProvider = NULL, $defaultNamingStrategy = NULL, $defaultVariant = NULL)
    {
        $this->eventDispatcher = $eventDispatcher;
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
        $extension = $file->guessExtension();
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function getProvider($name = NULL, $options = array())
    {
        if (!$name) {
            if(!$this->defaultProvider)
                throw new InvalidArgumentException('Trying to load the default Provider but it has not been set');

            $name = $this->defaultProvider;
        }

        return $this->providerFactory->get($name, $options);
    }

    /**
     * {@inheritDoc}
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
     * Processes a given media
     *
     * @param Model\Media $media
     * @param bool $isUpdate
     *
     * @throws Exception\VariantProcessingException
     * @return bool
     */
    protected function processMedia(Media $media, $isUpdate = FALSE)
    {
        $mediaEvent = new MediaEvent($media, $this);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_PROCESS, $mediaEvent);

        $context = $this->getContext($media->getContext());
        $provider = $this->getProvider($context->getProviderName(), $context->getProviderOptions());
        $variantsTree = $context->buildVariantTree();
        $filesystem = $this->getFilesystem($context->getFilesystemName());
        $namingStrategy = $this->getNamingStrategy($context->getNamingStrategyName());

        $generatedFiles = array();

        $variantsTree->visit(
            function(VariantNode $node, $level)
            use ($provider, $context, $media, $filesystem, $namingStrategy, &$generatedFiles, $isUpdate)
            {
                $variant = $node->getContent();
                $parent = $node->getParent() ? $node->getParent()->getContent() : NULL;
                if($isUpdate && $media->hasVariant($variant->getName()))
                {
                    $existingVariant = $media->getVariantInstance($variant->getName());
                    if($existingVariant->isReady())
                        $filesystem->delete($existingVariant->getFilename());
                    $media->removeVariant($variant->getName());
                }
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

        $this->eventDispatcher->dispatch(MediaEvents::AFTER_PROCESS, $mediaEvent);
        return TRUE; // marks the media as updated
    }

    /**
     * {@inheritDoc}
     */
    public function prepareMedia(Media $media, $isUpdate = false)
    {
        $context = $this->getContext($media->getContext());
        $provider = $this->getProvider($context->getProviderName(), $context->getProviderOptions());

        if(!$isUpdate || $isUpdate && $provider->hasChangedContent($media))
        {
            $mediaEvent = new MediaEvent($media, $this);
            $this->eventDispatcher->dispatch(MediaEvents::BEFORE_PREPARE, $mediaEvent);

            if(!$media->getContext())
                $media->setContext($context->getName());

            if( !$provider->validateContent($media->getContent()) )
                throw new InvalidContentException(sprintf('Invalid content of type "%s" for media "%s" detected by "%s" provider',
                        gettype($media->getContent())=='object'?get_class($media->getContent()):gettype($media->getContent()).'('.$media->getContent().')', $media, $provider->getName()),
                    $provider, $media);

            $provider->prepare($media, $context);
            $this->eventDispatcher->dispatch(MediaEvents::AFTER_PREPARE, $mediaEvent);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * {@inheritDoc}
     */
    public function saveMedia(Media $media)
    {
        $mediaEvent = new MediaEvent($media, $this);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_SAVE, $mediaEvent);
        $this->processMedia($media);
        $this->eventDispatcher->dispatch(MediaEvents::AFTER_SAVE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function updateMedia(Media $media)
    {
        $mediaEvent = new MediaEvent($media, $this);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_UPDATE, $mediaEvent);
        $this->processMedia($media, TRUE);
        $this->eventDispatcher->dispatch(MediaEvents::AFTER_UPDATE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function removeMedia(Media $media)
    {
        //TODO make removal of physical files asynchronous (optionally)
        $mediaEvent = new MediaEvent($media, $this);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_REMOVE, $mediaEvent);

        $context = $this->getContext($media->getContext());
        $filesystem = $this->getFilesystem($context->getFilesystemName());

        foreach($media->getVariants() as $name => $value)
        {
            $variant = $media->getVariantInstance($name);
            if($variant->isReady() && $filesystem->has($variant->getFilename()))
                $filesystem->delete($variant->getFilename());
        }

        $this->eventDispatcher->dispatch(MediaEvents::AFTER_REMOVE, $mediaEvent);
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
        $context = $this->getContext($media->getContext());

        if($variant === NULL)
        {
            if($context->getDefaultVariant() !== NULL)
                $variant = $context->getDefaultVariant();
            else
                $variant = $this->defaultVariant;
        }

        $provider = $this->getProvider($context->getProviderName(), $context->getProviderOptions());
        $variantInstance = $media->getVariantInstance($variant);

        $urlOptions = array();
        if(isset($options['_url']))
            $urlOptions = $options['_url'];

        return $provider->render($media, $variantInstance, $this->getUrl($media, $variant, $urlOptions), $options);
    }
}
