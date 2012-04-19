<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotLocateMediaException;
use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotStoreMediaException;

/**
 * Storage strategy based on local filesystem
 * @author Luciano Mammino
 */
class FilesystemMediaStorage implements IMediaStorage
{
    
    protected $mediaPath;
    protected $relativeBaseUrl;
    protected $absoluteBaseUrl;
    protected $useAbsoluteUrls;
    
    /**
     * Constructor
     * @param string 	$mediaPath 			the base path where media are stored
     * @param string 	$relativeBaseUrl 	the relative url to media path
     * @param string 	$absoluteBaseUrl 	the absolute url to media path
     * @param boolean 	$useAbsoluteUrls 	a boolean flag used to indicate wheter to use absolute urls
     */
    function __construct(   $mediaPath, 
                            $relativeBaseUrl, 
                            $absoluteBaseUrl, 
                            $useAbsoluteUrls    )
    {
        $this->mediaPath = $mediaPath;
        $this->relativeBaseUrl = $relativeBaseUrl;
        $this->absoluteBaseUrl = $absoluteBaseUrl;
        $this->useAbsoluteUrls = $useAbsoluteUrls;
    }

    public function getMediaPath()
    {
        return $this->mediaPath;
    }

    public function setMediaPath($mediaPath)
    {
        $this->mediaPath = $mediaPath;
    }

    public function getRelativeBaseUrl()
    {
        return $this->relativeBaseUrl;
    }

    public function setRelativeBaseUrl($relativeBaseUrl)
    {
        $this->relativeBaseUrl = $relativeBaseUrl;
    }

    public function getAbsoluteBaseUrl()
    {
        return $this->absoluteBaseUrl;
    }

    public function setAbsoluteBaseUrl($absoluteBaseUrl)
    {
        $this->absoluteBaseUrl = $absoluteBaseUrl;
    }

    public function getUseAbsoluteUrls()
    {
        return $this->useAbsoluteUrls;
    }

    public function setUseAbsoluteUrls($useAbsoluteUrls)
    {
        $this->useAbsoluteUrls = $useAbsoluteUrls;
    }

        
    protected function getPath($id, $name, $type, $variant)
    {
        //optimization to avoid having a lot of files in a sigle folder (which slows down unix systems)
        $subpath = substr( md5($id), 0, 2 );

        $parts = array($type, $subpath, preg_replace('/[^a-zA-Z0-9\s]/', '-', $id));
        if($variant)
            $parts[] = $variant;
        $parts[] = $name;

		return join(DIRECTORY_SEPARATOR, $parts);
    }
    
    protected function getFilename($id, $name, $type, $variant)
    {
        return $this->mediaPath
               . DIRECTORY_SEPARATOR 
               . $this->getPath($id, $name, $type, $variant);
    }

    /**
     * {@inheritDoc} 
     */
    public function locate($id, $name, $type, $variant = NULL)
    {
        if(preg_match('|^https?://.+$|iu', $name))
            return $name;

        $path = $this->getPath($id, $name, $type, $variant);
        $filename = $this->getFilename($id, $name, $type, $variant);
        
        if( !is_file( $filename ) || !is_readable( $filename ) )
        {
            throw new CannotLocateMediaException(
                    sprintf("File '%s' not found", $filename), $id, $name, $type, $variant);
        }
            
        $url = str_replace("\\", "/", $path);
        
        if($this->useAbsoluteUrls)
        {
            $url = $this->absoluteBaseUrl . $url;
        }
        else
        {
            $url = $this->relativeBaseUrl . $url;
        }
        
        return $url;
    }
    
    /**
     * {@inheritDoc} 
     */
    public function store($file, $id, $name, $type, $variant = NULL)
    {
        $dest = $this->getFilename($id, $name, $type, $variant);
        
        $destPath = substr($dest, 0, strripos($dest, DIRECTORY_SEPARATOR));
        if( !is_dir( $destPath ) )
        {
            if( is_file( $destPath ) )
            {
                throw new CannotStoreMediaException ("Unexpected file found '{$destPath}'", $id, $name, $type, $variant);
            }
            else if(!@mkdir($destPath, 0777, true))
            {
                throw new CannotStoreMediaException ("Cannot create '{$destPath}' folder", $id, $name, $type, $variant);
            }
        }
        
        if( !@copy($file, $dest) || @filesize( $file ) != @filesize( $dest ) )
            throw new CannotStoreMediaException ("Cannot copy '{$file}' to '{$dest}'", $id, $name, $type, $variant);
    }
    
}