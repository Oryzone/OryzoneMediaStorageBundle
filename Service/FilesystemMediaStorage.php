<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotLocateMediaException;
use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotStoreMediaException;

/**
 * Storage strategy based on local filesystem
 * @author Luciano Mammino
 */
class FilesystemMediaStorage extends  AbstractMediaStorage
{
    /**
     * the base path where media are stored
     *
     * @var string $mediaPath
     */
    protected $mediaPath;

	/**
	 * the relative url to media path
	 *
	 * @var string $relativeBaseUrl
	 */
    protected $relativeBaseUrl;

	/**
	 * The absolute url to media path
	 *
	 * @var string $absoluteBaseUrl
	 */
    protected $absoluteBaseUrl;

	/**
	 * A boolean flag used to indicate whether to use absolute urls
	 *
	 * @var boolean $absoluteUrlEnabled
	 */
    protected $absoluteUrlEnabled;

	/**
	 * A boolean flag used to indicate whether files should be moved instead that copied
	 *
	 * @var boolean $moveFileEnabled
	 */
	protected $moveFileEnabled;
    
    /**
     * Constructor
     * @param string 	$mediaPath 			the base path where media are stored
     * @param string 	$relativeBaseUrl 	the relative url to media path
     * @param string 	$absoluteBaseUrl 	the absolute url to media path
     * @param boolean 	$useAbsoluteUrls 	a boolean flag used to indicate whether to use absolute urls
     */
    function __construct(   $mediaPath, 
                            $relativeBaseUrl, 
                            $absoluteBaseUrl, 
                            $useAbsoluteUrls)
    {
        $this->mediaPath = $mediaPath;
        $this->relativeBaseUrl = $relativeBaseUrl;
        $this->absoluteBaseUrl = $absoluteBaseUrl;
        $this->absoluteUrlEnabled = $useAbsoluteUrls;
	    $this->moveFileEnabled       = false;
    }

	/**
	 * Get media path
	 *
	 * @return string
	 */
    public function getMediaPath()
    {
        return $this->mediaPath;
    }

	/**
	 * Set media path
	 *
	 * @param string $mediaPath
	 * @return FilesystemMediaStorage for fluent syntax
	 */
    public function setMediaPath($mediaPath)
    {
        $this->mediaPath = $mediaPath;
	    return $this;
    }

	/**
	 * Get relative base url
	 *
	 * @return string
	 */
    public function getRelativeBaseUrl()
    {
        return $this->relativeBaseUrl;
    }

	/**
	 * Set relative base url
	 *
	 * @param string $relativeBaseUrl
	 * @return FilesystemMediaStorage for fluent syntax
	 */
    public function setRelativeBaseUrl($relativeBaseUrl)
    {
        $this->relativeBaseUrl = $relativeBaseUrl;
	    return $this;
    }

	/**
	 * Get absolute base url
	 * @return string
	 */
    public function getAbsoluteBaseUrl()
    {
        return $this->absoluteBaseUrl;
    }

	/**
	 * Set absolute base url
	 *
	 * @param string $absoluteBaseUrl
	 * @return FilesystemMediaStorage for fluent syntax
	 */
    public function setAbsoluteBaseUrl($absoluteBaseUrl)
    {
        $this->absoluteBaseUrl = $absoluteBaseUrl;
	    return $this;
    }

	/**
	 * Check if absolute urls are enabled
	 *
	 * @return boolean
	 */
    public function isAbsoluteUrlEnabled()
    {
        return $this->absoluteUrlEnabled;
    }

	/**
	 * Enable or disable absolute urls
	 *
	 * @param boolean $enable
	 * @return FilesystemMediaStorage for fluent syntax
	 */
    public function enableAbsoluteUrl($enable = true)
    {
        $this->absoluteUrlEnabled = $enable;
	    return $this;
    }

	/**
	 * Set move files flag
	 *
	 * @param boolean $enable
	 * @return FilesystemMediaStorage for fluent syntax
	 */
	public function enableMoveFile($enable = true)
	{
		$this->moveFileEnabled = $enable;
		return $this;
	}

	/**
	 * Get move files flag
	 *
	 * @return boolean
	 */
	public function isMoveFileEnabled()
	{
		return $this->moveFileEnabled;
	}

    /**
     * Calculates a path by media attributes
     *
     * @param $id
     * @param $name
     * @param $type
     * @param $variant
     * @return string
     */
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

	/**
	 * Get the filename by media attributes
	 *
	 * @param $id
	 * @param $name
	 * @param $type
	 * @param $variant
	 * @return string
	 */
    protected function getFilename($id, $name, $type, $variant)
    {
        return $this->mediaPath
               . DIRECTORY_SEPARATOR 
               . $this->getPath($id, $name, $type, $variant);
    }

    /**
     * {@inheritDoc} 
     */
    public function locate($id, $name, $type, $variant = NULL, $fallbackToDefaultVariant = true)
    {
        if(preg_match('|^https?://.+$|iu', $name))
            return $name;

        $path = $this->getPath($id, $name, $type, $variant);
        $filename = $this->getFilename($id, $name, $type, $variant);
        
        if( !is_file( $filename ) || !is_readable( $filename ) )
        {
	        if($variant !== NULL && $fallbackToDefaultVariant)
		        return $this->locate($id, $name, $type, NULL, false);

            throw new CannotLocateMediaException(
                    sprintf("File '%s' not found", $filename), $id, $name, $type, $variant);
        }
            
        $url = str_replace("\\", "/", $path);
        
        if($this->absoluteUrlEnabled)
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
                throw new CannotStoreMediaException (sprintf('Unexpected file found "%s"', $destPath), $id, $name, $type, $variant);
            }
            else if(!@mkdir($destPath, 0777, true))
            {
                throw new CannotStoreMediaException (sprintf('Cannot create "%s" folder', $destPath), $id, $name, $type, $variant);
            }
        }

	    $originalFileSize = @filesize( $file );
	    $success = false;
	    $operation = '';

	    if($this->moveFileEnabled)
	    {
		    $success = @rename($file, $dest);
		    $operation = 'move';
	    }
	    else
	    {
			$success = @copy($file, $dest);
		    $operation = 'copy';
	    }

        if( !$success || $originalFileSize != @filesize( $dest ) )
            throw new CannotStoreMediaException (sprintf('Cannot %s "%s" (%d bytes) to "%s"', $operation, $file, ($originalFileSize?$originalFileSize:0), $dest), $id, $name, $type, $variant);
    }

	/**
	 * {@inheritDoc}
	 */
	public function getStorageStateId()
	{
		return sprintf('_m:%s,_r:%s,_a:%s,_e:%s', $this->mediaPath, $this->relativeBaseUrl, $this->absoluteBaseUrl, $this->absoluteUrlEnabled);
	}


}