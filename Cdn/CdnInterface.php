<?php

namespace Oryzone\Bundle\MediaStorageBundle\Cdn;

use Oryzone\Bundle\MediaStorageBundle\Model\Media;

interface CdnInterface
{
    /**
     * Retrieves the public url of the media on the current CDN
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @return string
     */
    public function getUrl(Media $media);
}
