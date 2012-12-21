<?php

namespace Oryzone\Bundle\MediaStorageBundle\Exception;

use Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface,
    Oryzone\Bundle\MediaStorageBundle\Model\Media;

class ProviderPrepareException extends MediaStorageException
{

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface $provider
     */
    protected $provider;

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     */
    protected $media;

    /**
     * Constructor
     *
     * @param string                                                        $message
     * @param \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface $provider
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media                $media
     * @param int                                                           $code
     * @param \Exception                                                    $previous
     */
    public function __construct($message = "", ProviderInterface $provider = NULL, Media $media = NULL, $code = 0, \Exception $previous = null)
    {
        $this->provider = $provider;
        $this->media = $media;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get media
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Model\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Get provider
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

}
