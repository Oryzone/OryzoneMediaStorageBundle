<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

use Symfony\Component\Form\FormBuilderInterface;

class DefaultFormTypeBuilder implements FormTypeBuilderInterface
{

    /**
     * {@inheritDoc}
     */
    public function buildMediaType(FormBuilderInterface $builder, $options = array())
    {
        $type = (isset($options['type']))? $options['type'] : 'file';
        $fieldOptions = array();
        if(isset($options['edit']) && $options['edit'] == TRUE)
            $fieldOptions = array('required' => FALSE);

        $builder->add('content', $type, $fieldOptions);
    }
}
