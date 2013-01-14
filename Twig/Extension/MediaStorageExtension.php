<?php

namespace Oryzone\Bundle\MediaStorageBundle\Twig\Extension;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Oryzone\Bundle\MediaStorageBundle\Templating\Helper\MediaStorageHelperInterface,
    Oryzone\MediaStorage\Model\MediaInterface;

class MediaStorageExtension extends \Twig_Extension
{

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Templating\Helper\MediaStorageHelperInterface $helper
     */
    protected $helper;

    /**
     * Constructor
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Templating\Helper\MediaStorageHelperInterface $helper
     */
    public function __construct(MediaStorageHelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'getMediaStorage' => new \Twig_Function_Method($this, 'getMediaStorage')
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array
        (
            'mediaUrl'      => new \Twig_Filter_Method($this, 'mediaUrl'),
            'mediaRender'   => new \Twig_Filter_Method($this, 'mediaRender', array( 'is_safe' => array('html')) )
        );
    }

    /**
     * Get media url
     *
     * @param  \Oryzone\MediaStorage\Model\MediaInterface $media
     * @param  null                                       $variant
     * @param  array                                      $options
     * @return string
     */
    public function mediaUrl(MediaInterface $media, $variant = NULL, $options = array())
    {
        return $this->helper->url($media, $variant, $options);
    }

    /**
     * Gets the instance of the media storage
     *
     * @return \Oryzone\MediaStorage\MediaStorageInterface
     */
    public function getMediaStorage()
    {
        return $this->helper->getMediaStorage();
    }

    /**
     * Renders a media
     *
     * @param  \Oryzone\MediaStorage\Model\MediaInterface $media
     * @param  null                                       $variant
     * @param  array                                      $options
     * @return string
     */
    public function mediaRender(MediaInterface $media, $variant = NULL, $options = array())
    {
        return $this->helper->render($media, $variant, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'oryzone_media_storage_twig_extension';
    }

}
