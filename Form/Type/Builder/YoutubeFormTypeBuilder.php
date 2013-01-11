<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\Type\Builder;

class YoutubeFormTypeBuilder extends VideoServiceFormTypeBuilder
{

    /**
     * Returns the canonical url scheme of the video service videos
     *
     * @return string
     */
    protected function getCanonicalUrl()
    {
        return 'http://www.youtube.com/watch?v=%s';
    }
}
