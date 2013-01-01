<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\Form\FormBuilderInterface;

use Oryzone\Bundle\MediaStorageBundle\Model\Media;

abstract class Provider implements ProviderInterface
{
    /**
     * Default content type (file).
     * Can be redefined in subclasses without the need to redefine the getContentType method
     *
     * @var int
     */
    protected static $contentType = self::CONTENT_TYPE_FILE;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var array $tempFiles
     */
    protected $tempFiles;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tempFiles = array();
        $this->options = array();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getContentType()
    {
        return self::$contentType;
    }

    /**
     * {@inheritDoc}
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->getDefaultOptions(), $options);
    }

    /**
     * Method used to define provider default options (to be rewritten if needed)
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array();
    }

    /**
     * {@inheritDoc}
     */
    public function hasChangedContent(Media $media)
    {
        return ($media->getContent() !== NULL);
    }


    /**
     * Adds a file to the list of temp files generated
     *
     * @param string $file
     */
    protected function addTempFile($file)
    {
        $this->tempFiles[] = $file;
    }

    /**
     * {@inheritDoc}
     */
    public function removeTempFiles()
    {
        foreach($this->tempFiles as $file)
            if(file_exists($file))
                unlink($file);
    }

    /**
     * {@inheritDoc}
     */
    public function buildMediaType(FormBuilderInterface $formBuilder, array $options = array())
    {
        $fieldTypes = array(
            self::CONTENT_TYPE_FILE => 'file',
            self::CONTENT_TYPE_INT => 'integer',
            self::CONTENT_TYPE_STRING => 'text'
        );

        $fieldOptions = array();
        if(isset($options['edit']) && $options['edit'] == TRUE)
            $fieldOptions = array('required' => FALSE);

        $formBuilder->add('content', $fieldTypes[self::$contentType], $fieldOptions);
    }

    /**
     * Transforms a media (from a form)
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @return mixed
     */
    public function transform(Media $media)
    {
        // does nothing
    }


}
