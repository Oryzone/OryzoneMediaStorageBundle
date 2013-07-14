<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */
use Symfony\Component\Form\AbstractType,
    Symfony\Component\Form\FormBuilderInterface,
    Symfony\Component\OptionsResolver\OptionsResolverInterface,
    Symfony\Component\Form\FormEvent,
    Symfony\Component\Form\FormEvents;

use Oryzone\MediaStorage\MediaStorageInterface,
    Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder\FormTypeBuilderFactoryInterface;

class MediaType extends AbstractType
{
    /**
     * Default media class
     */
    const DEFAULT_CLASS = 'Oryzone\Bundle\MediaStorageBundle\Entity\Media';

    /**
     * @var \Oryzone\MediaStorage\MediaStorageInterface $mediaStorage
     */
    protected $mediaStorage;

    /**
     * @var Builder\FormTypeBuilderFactoryInterface $formTypeBuilderFactory
     */
    protected $formTypeBuilderFactory;

    /**
     * @var null|string $class
     */
    protected $class;

    /**
     * Constructor
     *
     * @param \Oryzone\MediaStorage\MediaStorageInterface $mediaStorage
     * @param Builder\FormTypeBuilderFactoryInterface     $formTypeBuilderFactory
     * @param null|string                                 $class
     */
    public function __construct(MediaStorageInterface $mediaStorage, FormTypeBuilderFactoryInterface $formTypeBuilderFactory, $class = NULL)
    {
        if($class == NULL)
            $class = self::DEFAULT_CLASS;

        $this->mediaStorage = $mediaStorage;
        $this->formTypeBuilderFactory = $formTypeBuilderFactory;
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $context = $this->mediaStorage->getContext($options['context']);
        $provider = $this->mediaStorage->getProvider($context->getProviderName());

        //avoid to use a specific form field to define the context (context should be defined by the developer and must
        //not be changed by users)
        $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $e) use ($context){
            $e->getData()->setContextName($context->getName());
        });

        if($options['showName'])
            $builder->add('name', 'text', isset($options['name']) ? array('data' => $options['name']) : array());

        $formTypeBuilder = $this->formTypeBuilderFactory->get($provider->getName());
        $formTypeBuilder->buildMediaType($builder, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('context'))
                 ->setOptional(array('name', 'showName', 'edit'))
                 ->setDefaults(array(
                        'data_class' => $this->class,
                        'context'    => null,
                        'showName'  => TRUE,
                        'edit'      => FALSE
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
