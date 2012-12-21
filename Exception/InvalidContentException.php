<?php

namespace Oryzone\Bundle\MediaStorageBundle\Exception;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface;

class InvalidContentException extends MediaStorageException
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
     * @param string $message
     * @param \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface $provider
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = "", ProviderInterface $provider, Media $media, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->provider = $provider;
        $this->media = $media;
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
