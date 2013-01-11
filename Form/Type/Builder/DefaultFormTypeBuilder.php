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
