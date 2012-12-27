<?php

namespace Oryzone\Bundle\MediaStorageBundle\Integration\Video;

interface VideoServiceInterface
{

    /**
     * Issues a request to the service api to load information about a video
     *
     * @param string $id a unique identificative string for the video (generally the video id or the video url)
     * @param array $options an optional array of options
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\Integration\ResourceNotFoundException if cannot find the resource
     * (generally due to incorrect id)
     *
     * @return boolean <code>TRUE</code> if it loaded successfully
     */
    public function load($id, $options = array());

    /**
     * Gets the whole array of metadata
     *
     * @return array
     */
    public function getMetadata();

    /**
     * Gets a single metadata value for a given metadata key
     *
     * @param string $name the key of the metadata to retrieve
     * @param mixed $default a value to use as default response if the given key is not present in the metadata
     * @return mixed
     */
    public function getMetaValue($name, $default = NULL);

    /**
     * Gets the raw response from the service api (generally a JSON or XML encoded string)
     *
     * @return string
     */
    public function getRawResponse();

}
