<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type;

use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException,
    Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface,
    Oryzone\Bundle\MediaStorageBundle\Form\DataTransformer\ProviderDataTransformer;

class MediaType extends AbstractType
{
    /**
     * Default media class
     */
    const DEFAULT_CLASS = 'Oryzone\Bundle\MediaStorageBundle\Entity\Media';

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface $mediaStorage
     */
    protected $mediaStorage;

    /**
     * @var null|string $class
     */
    protected $class;

    /**
     * Constructor
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface $mediaStorage
     * @param null|string $class media class
     */
    public function __construct(MediaStorageInterface $mediaStorage, $class = NULL)
    {
        if($class == NULL)
            $class = self::DEFAULT_CLASS;

        $this->mediaStorage = $mediaStorage;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!isset($options['context']))
            throw new InvalidArgumentException('missing mandatory "context" option');

        $context = $this->mediaStorage->getContext($options['context']);
        $provider = $this->mediaStorage->getProvider($context->getProviderName());

        $builder->appendNormTransformer(new ProviderDataTransformer($provider, array(
            'context'  => $options['context'],
        )));

        $provider->buildMediaType($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'context'    => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'oryzone_media_storage_media';
    }
}