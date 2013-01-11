<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

interface FormTypeBuilderFactoryInterface
{

    /**
     * Get a form type builder with a given name
     *
     * @param  string                                                                        $formTypeBuilderName
     * @return \Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder\FormTypeBuilderInterface
     */
    public function get($formTypeBuilderName);

}
