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
