<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Exception\CannotLocateMediaException;
use Oryzone\Bundle\MediaStorageBundle\Exception\CannotStoreMediaException;

/**
 * Storage strategy based on local filesystem
 * @author Luciano Mammino
 */
class FilesystemSMediatorage implements IMediaStorage
{
    
    protected $mediaPath;
    protected $relativeBaseUrl;
    protected $absoluteBaseUrl;
    protected $useAbsoluteUrls;
    
    /**
     * Constructor
     * @param string 	$mediaPath 			the base path where media are stored
     * @param string 	$relativeBaseUrl 	the relative url to image path
     * @param string 	$absoluteBaseUrl 	the absolute url to image path
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
        $subpath = NULL;

		if(is_int($id))
			$subpath = $id % 256
		elseif(is_string($id))
			$subpath = substring( preg_replace('/[^a-zA-Z0-9\s]/', '_', $id),0,3 );
		else
			throw new \InvalidArgumentException('The parameter id can be only integer or string');

		return join(DIRECTORY_SEPARATOR, array(
                        $type,
                        $subpath,
                        $id,
                        $variant,
                        $name
                    ));
    }
    
    protected function getFilename($id, $name, $type, $variant)
    {
        return $this->imagesPath 
               . DIRECTORY_SEPARATOR 
               . $this->getPath($id, $name, $type, $variant);
    }

    /**
     * {@inheritDoc} 
     */
    public function locate($id, $name, $type, $variant = null)
    {
        $path = $this->getPath($id, $name, $type, $variant);
        $filename = $this->getFilename($id, $name, $type, $variant);
        
        if(!file_exists($filename))
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
    public function store($file, $id, $name, $type, $variant = null)
    {
        $dest = $this->getFilename($id, $name, $type, $variant);
        
        $destPath = substr($dest, 0, strripos($dest, DIRECTORY_SEPARATOR));
        if(!file_exists($destPath))
        {
            var_dump($destPath);
            if(!@mkdir($destPath, 0777, true))
            {
                throw new CannotStoreMediaException ("Cannot create '{$destPath}' folder", $id, $name, $type, $variant);
            }
        }
        
        if(!@copy($file, $dest))
            throw new CannotStoreMediaException ("Cannot copy '{$file}' to '{$dest}'", $id, $name, $type, $variant);
    }
    
}