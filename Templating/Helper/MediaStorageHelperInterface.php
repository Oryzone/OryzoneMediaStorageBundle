<?php

namespace Oryzone\Bundle\MediaStorageBundle\Templating\Helper;

use Oryzone\Bundle\MediaStorageBundle\Model\Media;

interface MediaStorageHelperInterface
{

    /**
     * Gets the path of a given media variant
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param string|null $variant
     * @param  array            $options
     *
     * @return string
     */
    public function path(Media $media, $variant = NULL, $options = array());

    /**
     * Gets the url of a given media variant
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param string|null $variant
     * @param  array            $options
     *
     * @return string
     */
    public function url(Media $media, $variant = NULL, $options = array());


    /**
     * Renders a media
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param null $variant
     * @param array $options
     *
     * @return string
     */
    public function render(Media $media, $variant = NULL, $options = array());

}
