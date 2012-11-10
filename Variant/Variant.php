<?php

namespace Oryzone\Bundle\MediaStorageBundle\Variant;

use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class Variant implements VariantInterface
{
    /**
     * Maps variant mode constants to identificative strings
     *
     * @var array
     */
    public static $VARIANT_MODE_MAP = array(
        'instant'   => self::MODE_INSTANT,
        'lazy'      => self::MODE_LAZY,
        'queue'     => self::MODE_QUEUE
    );

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $filename
     */
    protected $filename;

    /**
     * @var string $contentType
     */
    protected $contentType;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * @var int $mode
     */
    protected $mode;

    /**
     * @var int $status
     */
    protected $status;

    /**
     * @var string $error
     */
    protected $error;

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set the filename
     *
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritDoc}
     */
    public function getContentType()
    {
        return $this->getContentType();
    }

    /**
     * Set the contentType
     *
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set the options
     *
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the mode
     *
     * @param int $mode
     */
    public function setMode($mode)
    {
        if(is_int($mode))
        {
            if(!in_array($mode, self::$VARIANT_MODE_MAP))
                throw new InvalidArgumentException(sprintf('Variant mode "%s" is not supported', $mode));
        }
        elseif(is_string($mode))
        {
            if(!array_key_exists($mode, self::$VARIANT_MODE_MAP))
                throw new InvalidArgumentException(sprintf('Variant mode "%s" is not supported', $mode));

            $mode = self::$VARIANT_MODE_MAP[$mode];
        }
        $this->mode = $mode;
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the status
     *
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * {@inheritDoc}
     */
    public function isReady()
    {
        return ($this->status == self::STATUS_READY);
    }

    /**
     * {@inheritDoc}
     */
    public function hasError()
    {
        return ($this->status == self::STATUS_ERROR);
    }

    /**
     * {@inheritDoc}
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the error
     *
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        $data = array(
            'name'          => $this->name,
            'filename'      => $this->filename,
            'contentType'   => $this->contentType,
            'options'       => $this->options,
            'status'        => $this->status
        );

        if($this->hasError())
            $data['error'] = $this->error;

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public static function fromArray($array)
    {
        $variant = new Variant();
        if(isset($array['name']))
            $variant->setName($array['name']);
        if(isset($array['filename']))
            $variant->setFilename($array['filename']);
        if(isset($array['contentType']))
            $variant->setContentType($array['contentType']);
        if(isset($array['options']))
            $variant->setOptions($array['options']);
        if(isset($array['status']))
            $variant->setStatus($array['status']);
        if(isset($array['error']))
            $variant->setError($array['error']);

        return $variant;
    }
}
