<?php

namespace Oryzone\Bundle\MediaStorageBundle\Event;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\EventDispatcher\Event;

use Oryzone\MediaStorage\Model\MediaInterface;

class MediaEvent extends Event
{

    /**
     * @var \Oryzone\MediaStorage\Model\MediaInterface $media
     */
    protected $media;

    /**
     * Constructor
     *
     * @param \Oryzone\MediaStorage\Model\MediaInterface $media
     */
    public function __construct(MediaInterface $media)
    {
        $this->media = $media;
    }

    /**
     * Get media
     *
     * @return \Oryzone\MediaStorage\Model\MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }

}
