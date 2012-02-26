<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Entity\IMedia;
use Oryzone\Bundle\MediaStorageBundle\Exception\CannotLocateMediaException;

/**
 * Twig extension written to ease the usage of media
 *   storage within templates
 *
 * @author Luciano Mammino
 */
class TwigMediaStorageExtension extends \Twig_Extension
{

    /**
     * @var IMediaStorage
     */
    protected $mediaStorage;
    protected $debug;
    
    public function __construct($mediaStorage, $debug = false)
    {
        $this->mediaStorage = $mediaStorage;
        $this->debug = $debug;
    }
    
    public function getFilters()
    {
        return array(
            'locateSource' => new \Twig_Filter_Method($this, 'locateSourceFilter'),
        );
    }
    
    public function locateSourceFilter(IMedia $media, $variant = NULL)
    {
        try
        {
            $url = $this->mediaStorage->locate($media->getId(), $media->getMediaName(), $media->getMediaType(), $variant);
            return $url;
        }
        catch( CannotLocateImageException $e)
        {
            if($this->debug)
                throw $e;
        }
        
        return "";
    }
    
    public function getName()
    {
        return 'twigMediaStorage';
    }
   
}