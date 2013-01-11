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

use Symfony\Component\Form\FormBuilderInterface;

interface FormTypeBuilderInterface
{

    /**
     * Builds the form using the given builder
     *
     * @param  \Symfony\Component\Form\FormBuilderInterface $builder
     * @param  array                                        $options
     * @return mixed
     */
    public function buildMediaType(FormBuilderInterface $builder, $options = array());

}
