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
