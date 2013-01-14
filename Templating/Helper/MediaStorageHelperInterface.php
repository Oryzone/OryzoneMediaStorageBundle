<?php

namespace Oryzone\Bundle\MediaStorageBundle\Templating\Helper;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Oryzone\MediaStorage\Model\MediaInterface;

interface MediaStorageHelperInterface
{

    /**
     * Get the current instance of the media storage
     *
     * @return \Oryzone\MediaStorage\MediaStorageInterface
     */
    public function getMediaStorage();

    /**
     * Gets the url of a given media variant
     *
     * @param \Oryzone\MediaStorage\Model\MediaInterface $media
     * @param string|null                                $variant
     * @param array                                      $options
     *
     * @return string
     */
    public function url(MediaInterface $media, $variant = NULL, $options = array());

    /**
     * Renders a media
     *
     * @param \Oryzone\MediaStorage\Model\MediaInterface $media
     * @param null                                       $variant
     * @param array                                      $options
     *
     * @return string
     */
    public function render(MediaInterface $media, $variant = NULL, $options = array());

}
