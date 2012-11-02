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
     * Get the filename of the file associated with the variant
     * Used if the variant is stored as a file
     *
     * @return string
     */
    public function getFileName();

    /**
     * Get the content type (mime type) of the file associated with the variant
     * Used if the variant is stored as a file
     *
     * @return string
     */
    public function getContentType();

    /**
     * Get the array of options to use (or used) for processing
     *
     * @return array
     */
    public function getOptions();

    /**
     * Get the processing mode.
     * Should not be serialized (because, if necessary, the current state should be enough to give an
     * idea of the processing mode used)
     *
     * @return int
     */
    public function getMode();

    /**
     * Get the current variant state
     *
     * @return int
     */
    public function getState();

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