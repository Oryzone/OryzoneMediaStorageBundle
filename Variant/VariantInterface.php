<?php

namespace Oryzone\Bundle\MediaStorageBundle\Variant;

interface VariantInterface
{

    /**
     * Status used to mark a variant as ready (correctly processed and stored)
     */
    const STATUS_READY = 1;

    /**
     * Status used to mark a variant that is waiting to be used for the first time before being
     * processed (lazy processing)
     */
    const STATUS_ON_DEMAND = 2;

    /**
     * Status used to mark a variant that has been put in a processing queue and it's waiting its turn to be processed
     */
    const STATUS_QUEUED = 3;

    /**
     * Status used to mark a variant that is currently under processing
     */
    const STATUS_PROCESSING = 4;

    /**
     * Status used to mark a variant that has been processed but processing raised an error
     */
    const STATUS_ERROR = 5;

    /**
     * Status used to mark an invalidated variant (useful when variants are generated on demand an you want to regenerate
     * the variant the next time it's requested)
     */
    const STATUS_INVALIDATED = 6;

    /**
     * Mode used when the variant should be processed instantaneously
     */
    const MODE_INSTANT = 0;

    /**
     * Mode used when the variant should be processed on demand the first time used
     */
    const MODE_LAZY = 1;

    /**
     * Mode used when the variant is put in a queue of processing
     */
    const MODE_QUEUE = 2;

    /**
     * Get the name of the variant
     *
     * @return string
     */
    public function getName();

    /**
     * Sets name
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Get the filename of the file associated with the variant
     * Used if the variant is stored as a file
     *
     * @return string
     */
    public function getFilename();

    /**
     * Sets filename
     *
     * @param string $filename
     */
    public function setFilename($filename);

    /**
     * Sets the whole array of metadata for the variant
     *
     * @param array $meta
     * @return mixed
     */
    public function setMeta($meta);

    /**
     * Get the whole array of metadata related to the variant
     *
     * @return array
     */
    public function getMeta();

    /**
     * Set a value for a specific metadata
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setMetaValue($key, $value);

    /**
     * Get
     *
     * @param string $key
     * @param mixed|null $default a default value in case the given key is not present
     * @return mixed
     */
    public function getMetaValue($key, $default = NULL);

    /**
     * Get the content type (mime type) of the file associated with the variant
     * Used if the variant is stored as a file
     *
     * @return string
     */
    public function getContentType();

    /**
     * Sets contentType
     *
     * @param string $contentType
     */
    public function setContentType($contentType);

    /**
     * Get the array of options to use (or used) for processing
     *
     * @return array
     */
    public function getOptions();

    /**
     * Sets options
     *
     * @param array $options
     */
    public function setOptions($options);

    /**
     * Get the processing mode.
     *
     * @return int
     */
    public function getMode();

    /**
     * Sets mode
     *
     * @param int $mode
     */
    public function setMode($mode);

    /**
     * Get the current variant status
     *
     * @return int
     */
    public function getStatus();

    /**
     * Sets the status
     *
     * @param int $status
     */
    public function setStatus($status);

    /**
     * Returns <code>TRUE</code> if the current variant has been successfully processed and it's ready to be used
     *
     * @return boolean
     */
    public function isReady();

    /**
     * Checks if the current variant has errors
     *
     * @return boolean
     */
    public function hasError();

    /**
     * Get the error string that describes an eventual error occurred on processing
     *
     * @return string
     */
    public function getError();

    /**
     * Invalidates the current variant
     */
    public function invalidate();

    /**
     * Serializes the object to an array
     *
     * @return array
     */
    public function toArray();

    /**
     * Creates a variant from an array
     * @param array $array
     *
     * @return VariantInterface
     */
    public static function fromArray($array);

}
