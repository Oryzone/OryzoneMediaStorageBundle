<?php

namespace Oryzone\Bundle\MediaStorageBundle;

use Oryzone\Bundle\MediaStorageBundle\Model\Media;

interface MediaStorageInterface
{

    /**
     * Prepares a media to be stored
     *
     * @param  Model\Media $media
     * @param  bool        $isUpdate
     * @return mixed
     */
    public function prepareMedia(Media $media, $isUpdate = FALSE);

    /**
     * Saves a media
     *
     * @param  Model\Media $media
     * @return mixed
     */
    public function saveMedia(Media $media);

    /**
     * Update media
     *
     * @param  Model\Media $media
     * @return mixed
     */
    public function updateMedia(Media $media);

    /**
     * Removes (deletes) a media and connected files
     *
     * @param  Model\Media $media
     * @return mixed
     */
    public function removeMedia(Media $media);

    /**
     * Get the url of a media file (if any)
     *
     * @param  Model\Media $media
     * @param  string|null      $variant
     * @param  array            $options
     *
     * @return string
     */
    public function getUrl(Media $media, $variant = NULL, $options = array());

    /**
     * Renders a given media
     *
     * @param Model\Media $media
     * @param null $variant
     * @param array $options
     * @return mixed
     */
    public function render(Media $media, $variant = NULL, $options = array());


    /**
     * Loads a cdn with a given name
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default cdn
     * @return Cdn\CdnInterface
     * @throws Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface
     */
    public function getCdn($name = NULL);

    /**
     * Loads a context with a given name
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default context
     * @return Context\ContextInterface
     * @throws Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Context\ContextInterface
     */
    public function getContext($name = NULL);

    /**
     * Loads a filesystem with a given filesystem
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default filesystem
     * @return \Gaufrette\Filesystem
     * @throws Exception\InvalidArgumentException
     *
     * @return \Gaufrette\Filesystem
     */
    public function getFilesystem($name = NULL);

    /**
     * Loads a provider with a given name
     *
     * @param  string|null                        $name if <code>NULL</code> will load the default provider
     * @return Provider\ProviderInterface
     * @throws Exception\InvalidArgumentException
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface
     */
    public function getProvider($name = NULL);

    /**
     * Loads a naming strategy with a given name
     *
     * @param null $name
     * @throws Exception\InvalidArgumentException
     * @param  string|null                        $name
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\NamingStrategy\NamingStrategyInterface
     */
    public function getNamingStrategy($name = NULL);

    /**
     * Get the name of a variant (fallbacks to default if null is given)
     *
     * @param string|null $name
     * @return null|string
     * @throws Exception\InvalidArgumentException
     */
    public function getVariant($name = NULL);

}
