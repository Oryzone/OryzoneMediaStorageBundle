<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

use Symfony\Component\Form\FormBuilderInterface;

use Oryzone\Bundle\MediaStorageBundle\Form\DataTransformer\VideoServiceDataTransformer;

abstract class VideoServiceFormTypeBuilder implements FormTypeBuilderInterface
{

    /**
     * Returns the canonical url scheme of the video service videos
     *
     * @return string
     */
    abstract protected function getCanonicalUrl();

    /**
     * {@inheritDoc}
     */
    public function buildMediaType(FormBuilderInterface $builder, $options = array())
    {
        $fieldOptions = array();
        if(isset($options['edit']) && $options['edit'] == TRUE)
            $fieldOptions = array('required' => FALSE);

        $builder->add('content', 'text', $fieldOptions)
            ->addViewTransformer(new VideoServiceDataTransformer($this->getCanonicalUrl()));
    }
}
