<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

use Symfony\Component\Form\FormBuilderInterface;

interface FormTypeBuilderInterface
{

    /**
     * Builds the form using the given builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     * @return mixed
     */
    public function buildMediaType(FormBuilderInterface $builder, $options = array());

}
