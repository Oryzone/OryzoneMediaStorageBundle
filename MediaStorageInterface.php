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
     * Get the local path of a media file (if any)
     *
     * @param  Model\Media      $media
     * @param  string|null      $variant
     * @param  array            $options
     *
     * @return string
     */
    public function getPath(Media $media, $variant = NULL, $options = array());

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
}
