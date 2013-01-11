<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

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
