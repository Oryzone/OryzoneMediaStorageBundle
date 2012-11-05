<?php

namespace Oryzone\Bundle\MediaStorageBundle;

use Oryzone\Bundle\MediaStorageBundle\Model\Media;

interface MediaStorageInterface
{

    /**
     * Prepares a media to be stored
     *
     * @param Model\Media $media
     * @return mixed
     */
    public function prepareMedia(Media $media);

    /**
     * Saves a media
     *
     * @param Model\Media $media
     * @return mixed
     */
    public function saveMedia(Media $media);

    /**
     * Update media
     *
     * @param Model\Media $media
     * @return mixed
     */
    public function updateMedia(Media $media);

    /**
     * Removes (deletes) a media and connected files
     *
     * @param Model\Media $media
     * @return mixed
     */
    public function removeMedia(Media $media);

    /**
     * Get the local path of a media file (if any)
     *
     * @param Model\Media $media
     * @return mixed
     */
    public function getPath(Media $media);

    /**
     * Get the url of a media file (if any)
     *
     * @param Model\Media $media
     * @return mixed
     */
    public function getUrl(Media $media);
}