<?php

namespace Oryzone\Bundle\MediaStorageBundle\Cdn;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class LocalCdn implements CdnInterface
{
    /**
     * @var string $path
     */
    protected $path;

    /**
     * {@inheritDoc}
     */
    public function setConfiguration($configuration)
    {
        if(!isset($configuration['path']))
            throw new InvalidArgumentException('Missing mandatory "path" option');

        $this->path = $configuration['path'];
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(Media $media, VariantInterface $variant, $options = array())
    {
        $url = $this->path . $variant->getFilename();

        if(isset($options['absolute']) && $options['absolute'])
        {
            if(isset($options['domain']))
                $domain = $options['domain'];
            else
                $domain = $_SERVER['HTTP_HOST'];

            if(isset($options['protocol']))
                $protocol = $options['protocol'];
            else
                $protocol = isset($_SERVER['HTTPS']) ? 'https' : 'http';

            $url = sprintf('%s://%s/%s', $protocol, $domain, ltrim($url, '/'));
        }

        return $url;
    }
}
