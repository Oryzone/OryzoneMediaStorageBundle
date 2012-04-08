<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Entity\IMedia;
use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotLocateMediaException;

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

    public function getGlobals()
    {
        return array(
            'MediaStorage_instance' => $this->mediaStorage,
            'MediaStorage_instance_debug' => $this->debug,
        );
    }

    public function getFilters()
    {
        return array(
            'locateSrc' => new \Twig_Filter_Method($this, 'locateSrcFilter'),
        );
    }


    public function getFunctions()
    {
        return array(
            'locateSrc' => new \Twig_Function_Function('Oryzone\Bundle\MediaStorageBundle\Service\locateSrc',
                                array( 'is_safe' => array('html'), 'needs_context' => FALSE, 'needs_environment' => TRUE) ),
        );
    }


    public function locateSrcFilter(IMedia $media, $variant = NULL)
    {
        try
        {
            if($media->isMediaExternal())
                return $media->getMediaName();

            $url = $this->mediaStorage->locate($media->getMediaId(), $media->getMediaName(), $media->getMediaType(), $variant);
            return $url;
        }
        catch( CannotLocateMediaException $e)
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

function locateSrc(\Twig_Environment $env, $id, $name, $type, $variant = NULL)
{
    $globals = $env->getGlobals();

    try
    {
        $url = $globals['MediaStorage_instance']->locate($id, $name, $type, $variant);
        return $url;
    }
    catch( CannotLocateMediaException $e)
    {
        if($globals['MediaStorage_instance_debug'])
            throw $e;
    }

    return "";
}