<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

class VimeoFormTypeBuilder extends VideoServiceFormTypeBuilder
{

    /**
     * Returns the canonical url scheme of the video service videos
     *
     * @return string
     */
    protected function getCanonicalUrl()
    {
        return 'http://vimeo.com/%s';
    }
}
