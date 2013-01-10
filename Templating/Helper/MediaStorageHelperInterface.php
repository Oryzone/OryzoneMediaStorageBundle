<?php

namespace Oryzone\Bundle\MediaStorageBundle\Templating\Helper;

use Oryzone\MediaStorage\Model\MediaInterface;

interface MediaStorageHelperInterface
{

    /**
     * Gets the url of a given media variant
     *
     * @param \Oryzone\MediaStorage\Model\MediaInterface $media
     * @param string|null $variant
     * @param  array            $options
     *
     * @return string
     */
    public function url(MediaInterface $media, $variant = NULL, $options = array());


    /**
     * Renders a media
     *
     * @param \Oryzone\MediaStorage\Model\MediaInterface $media
     * @param null $variant
     * @param array $options
     *
     * @return string
     */
    public function render(MediaInterface $media, $variant = NULL, $options = array());

}
